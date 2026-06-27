<?php

namespace App\Http\Controllers;

use App\Mail\LoginCodeMail;
use App\Mail\AccountSetupLinkMail;
use App\Mail\PasswordResetLinkMail;
use App\Models\User;
use App\Support\AppSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showVerifyCode(Request $request)
    {
        abort_unless($request->session()->has('login_code_user_id'), 404);

        return view('auth.verify-code', [
            'email' => $request->session()->get('login_code_email'),
        ]);
    }

    public function login(Request $request)
    {
        $request->merge([
            'email' => $this->normalizeEmail($request->input('email')),
        ]);

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        if (! $user->can_login) {
            return back()->withErrors([
                'email' => 'Your access is currently disabled. Please contact an administrator.',
            ])->onlyInput('email');
        }

        if ((int) $user->is_admin === 1 || $user->is_admin === true) {
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            return redirect()->intended('/admin');
        }

        return $this->beginLoginCodeChallenge($request, $user, $request->boolean('remember'));
    }

    public function verifyCode(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $userId = $request->session()->get('login_code_user_id');

        abort_unless($userId, 404);

        $user = User::findOrFail($userId);

        if (! $user->login_code || ! $user->login_code_expires_at || $user->login_code_expires_at->isPast()) {
            throw ValidationException::withMessages([
                'code' => 'That login code has expired. Please sign in again to request a new one.',
            ]);
        }

        if (! Hash::check($validated['code'], $user->login_code)) {
            throw ValidationException::withMessages([
                'code' => 'That login code is incorrect.',
            ]);
        }

        $user->update([
            'login_code' => null,
            'login_code_expires_at' => null,
        ]);

        Auth::login($user, (bool) $request->session()->pull('login_code_remember', false));
        $request->session()->forget([
            'login_code_user_id',
            'login_code_email',
        ]);
        $request->session()->regenerate();

        if ((int) $user->is_admin === 1 || $user->is_admin === true) {
            return redirect()->intended('/admin');
        }

        return redirect('/app');
    }

    public function showRegister()
    {
        abort_unless(AppSettings::all()['registration_enabled'], 404);

        return view('auth.register');
    }

    public function register(Request $request)
    {
        $settings = AppSettings::all();

        abort_unless($settings['registration_enabled'], 404);

        $request->merge([
            'name' => trim((string) $request->input('name')),
            'email' => $this->normalizeEmail($request->input('email')),
            'event_access_code' => trim((string) $request->input('event_access_code')),
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
            'event_access_code' => ['required', 'string', 'max:255'],
        ]);

        $expectedAccessCode = $this->normalizeEventAccessCode($settings['event_access_code'] ?? '');
        $providedAccessCode = $this->normalizeEventAccessCode($validated['event_access_code']);

        if ($expectedAccessCode !== '' && $providedAccessCode !== $expectedAccessCode) {
            throw ValidationException::withMessages([
                'event_access_code' => 'That event access code is incorrect.',
            ]);
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'can_login' => true,
        ]);

        try {
            return $this->beginLoginCodeChallenge($request, $user, false);
        } catch (\Throwable $exception) {
            Log::error('Registration login code delivery failed.', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $exception->getMessage(),
            ]);

            $user->delete();

            throw ValidationException::withMessages([
                'email' => 'We could not finish creating your account because the login code email could not be sent right now. Please try again in a few minutes.',
            ]);
        }
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->merge([
            'email' => $this->normalizeEmail($request->input('email')),
        ]);

        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if ($user) {
            try {
                $this->sendPasswordResetLink($user);
            } catch (\Throwable $exception) {
                Log::error('Password reset email delivery failed.', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'error' => $exception->getMessage(),
                ]);

                throw ValidationException::withMessages([
                    'email' => 'We could not send your password reset email right now. Please try again in a few minutes.',
                ]);
            }
        }

        return back()->with('success', 'If that email is in our system, we sent a password reset link.');
    }

    public function showResetPassword(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => (string) $request->query('email', ''),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->merge([
            'email' => $this->normalizeEmail($request->input('email')),
        ]);

        $validated = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $status = Password::reset(
            $validated,
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'login_code' => null,
                    'login_code_expires_at' => null,
                ])->save();
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            $message = match ($status) {
                Password::INVALID_TOKEN => 'That password setup link is no longer valid. Please use the newest email we sent, or request a fresh reset link from the login page.',
                Password::INVALID_USER => 'We could not find an account that matches that setup link. Please request a fresh reset link from the login page.',
                default => __($status),
            };

            throw ValidationException::withMessages([
                'email' => $message,
            ]);
        }

        return redirect('/login')->with('success', 'Your password has been updated. You can sign in now.');
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user) {
            $user->forceFill([
                'login_code' => null,
                'login_code_expires_at' => null,
            ])->save();
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function beginLoginCodeChallenge(Request $request, User $user, bool $remember)
    {
        $code = (string) random_int(100000, 999999);

        $user->update([
            'login_code' => Hash::make($code),
            'login_code_expires_at' => Carbon::now()->addMinutes(10),
        ]);

        try {
            Mail::to($user->email)->send(new LoginCodeMail($user, $code));
        } catch (\Throwable $exception) {
            Log::error('Login code email delivery failed.', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $exception->getMessage(),
            ]);

            throw ValidationException::withMessages([
                'email' => 'We could not send your login code right now. Please try again in a few minutes.',
            ]);
        }

        $request->session()->put([
            'login_code_user_id' => $user->id,
            'login_code_email' => $user->email,
            'login_code_remember' => $remember,
        ]);

        return redirect('/login/verify')->with('success', 'We emailed a login code to '.$user->email.'.');
    }

    public function sendPasswordResetLink(User $user): void
    {
        $token = Password::broker()->createToken($user);
        $resetUrl = url('/reset-password/'.$token.'?email='.urlencode($user->email));

        Mail::to($user->email)->send(new PasswordResetLinkMail($user, $resetUrl));
    }

    public function sendPasswordSetupLink(User $user): void
    {
        $token = Password::broker()->createToken($user);
        $setupUrl = url('/reset-password/'.$token.'?email='.urlencode($user->email));

        Mail::to($user->email)->send(new AccountSetupLinkMail($user, $setupUrl));
    }

    private function normalizeEmail(mixed $email): string
    {
        return Str::lower(trim((string) $email));
    }

    private function normalizeEventAccessCode(mixed $code): string
    {
        return Str::lower(trim((string) $code));
    }
}
