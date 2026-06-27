@php($settings = \App\Support\AppSettings::all())
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your login code</title>
</head>
<body style="margin:0; padding:32px 20px; background:#f4f6fb; font-family:Arial, sans-serif; color:#171a37;">
    <div style="max-width:640px; margin:0 auto; background:#ffffff; border-radius:20px; padding:32px; box-shadow:0 10px 30px rgba(17,26,55,0.08);">
        <p style="margin:0 0 12px; color:#ff4d6d; font-size:12px; font-weight:700; letter-spacing:0.12em; text-transform:uppercase;">
            {{ $settings['brand_name'] }} Login
        </p>
        <h1 style="margin:0 0 16px; font-size:32px; line-height:1.05;">Your verification code</h1>
        <p style="margin:0 0 20px; color:#5d627d; font-size:16px; line-height:1.7;">
            Use the code below to finish signing in to {{ $settings['brand_name'] }}.
        </p>

        <div style="display:inline-block; padding:16px 20px; border-radius:18px; background:#171a37; color:#ffffff; font-size:34px; font-weight:800; letter-spacing:0.22em;">
            {{ $code }}
        </div>

        <p style="margin:24px 0 0; color:#5d627d; font-size:14px; line-height:1.6;">
            This code expires in 10 minutes. If you did not try to sign in, you can ignore this email.
        </p>
    </div>
</body>
</html>
