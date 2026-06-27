@php($settings = \App\Support\AppSettings::all())
@php($emailLogo = !empty($settings['brand_logo_path'] ?? null) ? asset('storage/'.$settings['brand_logo_path']) : ($settings['brand_logo_url'] ?? null))
@php($brandName = $settings['brand_name'] ?? 'Conference App')
@php($brandTagline = $settings['brand_tagline'] ?? 'Conference app access')

<div style="margin:0; padding:24px 0; background:#f3f5fb;">
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="border-collapse:collapse;">
        <tr>
            <td align="center" style="padding:0 16px;">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width:620px; border-collapse:collapse;">
                    <tr>
                        <td style="padding:0 0 18px;">
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="border-collapse:collapse;">
                                <tr>
                                    <td style="font-family:'Avenir Next','Segoe UI',sans-serif; color:#171a37;">
                                        @if ($emailLogo)
                                            <img src="{{ $emailLogo }}" alt="{{ $brandName }} logo" style="width:68px; height:68px; object-fit:contain; border-radius:18px; display:block; margin:0 0 14px;">
                                        @endif
                                        <div style="font-size:1.5rem; font-weight:800; letter-spacing:-0.03em;">{{ $brandName }}</div>
                                        <div style="margin-top:4px; color:#55607d; font-size:0.96rem;">{{ $brandTagline }}</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="background:#ffffff; border:1px solid rgba(23,26,55,0.08); border-radius:28px; box-shadow:0 18px 46px rgba(16,22,47,0.08); overflow:hidden;">
                            <div style="height:10px; background:linear-gradient(90deg, #b12549, #ff6c85 58%, #b8d8cf);"></div>
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="border-collapse:collapse;">
                                <tr>
                                    <td style="padding:32px 32px 28px; font-family:'Avenir Next','Segoe UI',sans-serif; color:#171a37;">
                                        <div style="display:inline-block; padding:7px 12px; border-radius:999px; background:rgba(177,37,73,0.1); color:#9c1f44; font-size:0.78rem; font-weight:800; letter-spacing:0.08em; text-transform:uppercase;">Account Setup</div>
                                        <h1 style="margin:18px 0 12px; font-size:2rem; line-height:1.02; letter-spacing:-0.05em; font-weight:800; color:#171a37;">Welcome to {{ $brandName }}</h1>
                                        <p style="margin:0 0 14px; font-size:1rem; line-height:1.7; color:#33415f;">Hello {{ $user->name }},</p>
                                        <p style="margin:0 0 14px; font-size:1rem; line-height:1.7; color:#33415f;">Your account has been created. To activate access, please use the secure link below to set your password.</p>
                                        <p style="margin:0 0 14px; font-size:1rem; line-height:1.7; color:#33415f;">Once your password is set, you can sign in to the app and access event updates, session details, and your attendee tools.</p>
                                        <p style="margin:24px 0 22px;">
                                            <a href="{{ $setupUrl }}" style="display:inline-block; padding:14px 22px; border-radius:999px; background:#b12549; color:#ffffff; text-decoration:none; font-size:0.98rem; font-weight:800;">Set Up Your Password</a>
                                        </p>
                                        <div style="padding:16px 18px; border-radius:18px; background:#f5f7fb; border:1px solid rgba(23,26,55,0.06); color:#55607d; font-size:0.95rem; line-height:1.65;">
                                            For security, only the most recent password setup email will work. If you receive more than one, use the newest link. If you were not expecting this email, no further action is required.
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
