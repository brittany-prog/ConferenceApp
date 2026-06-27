@php($settings = \App\Support\AppSettings::all())
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $announcement->title }}</title>
</head>
<body style="margin:0; padding:32px 20px; background:#f4f6fb; font-family:Arial, sans-serif; color:#171a37;">
    <div style="max-width:640px; margin:0 auto; background:#ffffff; border-radius:20px; padding:32px; box-shadow:0 10px 30px rgba(17,26,55,0.08);">
        <p style="margin:0 0 12px; color:#ff4d6d; font-size:12px; font-weight:700; letter-spacing:0.12em; text-transform:uppercase;">
            {{ $settings['brand_name'] }} Announcement
        </p>
        <h1 style="margin:0 0 16px; font-size:32px; line-height:1.05;">{{ $announcement->title }}</h1>
        <p style="margin:0 0 20px; color:#5d627d; font-size:16px; line-height:1.7;">
            {!! nl2br(e($announcement->message)) !!}
        </p>

        <div style="margin-top:28px; padding-top:20px; border-top:1px solid rgba(23,26,55,0.1);">
            <p style="margin:0 0 8px; font-weight:700;">{{ $settings['brand_name'] }}</p>
            <p style="margin:0; color:#5d627d;">{{ collect([$settings['event_date_range_label'], $settings['event_city_region']])->filter()->implode(' | ') }}</p>
        </div>
    </div>
</body>
</html>
