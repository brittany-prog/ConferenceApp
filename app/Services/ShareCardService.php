<?php

namespace App\Services;

use App\Models\User;
use App\Models\Session;
use App\Support\AppSettings;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ShareCardService
{
    public function attendeeCaption(User $user): string
    {
        $settings = AppSettings::all();
        $brandName = $settings['brand_name'] ?? 'this event';
        $venueName = $settings['venue_name'] ?? null;
        $dateLabel = $settings['event_date_range_label'] ?? null;
        $cityLabel = $settings['event_city_region'] ?? null;
        $roleLine = trim(collect([$user->title, $user->organization])->filter()->implode(' at '));
        $intro = $roleLine !== ''
            ? "{$user->name}, {$roleLine}, is attending {$brandName}"
            : "{$user->name} is attending {$brandName}";

        $details = collect([$venueName, $dateLabel, $cityLabel])->filter()->implode(' on ');

        return trim($intro.($details ? ' at '.$details.'.' : '.').' Looking forward to the event.');
    }

    public function attendeeSvg(User $user): string
    {
        $background = $this->dataUri(public_path('share-cards/attendee-template.png'));
        $profilePhoto = $this->profilePhotoDataUri($user);
        $nameLines = $this->wrapText($user->name, 18, 2);

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="1080" height="1350" viewBox="0 0 1080 1350" fill="none">
  <defs>
    <clipPath id="attendee-photo-clip">
      <rect x="92" y="548" width="404" height="404" rx="52" ry="52"/>
    </clipPath>
    <filter id="attendee-photo-shadow" x="48" y="510" width="500" height="500" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
      <feDropShadow dx="0" dy="18" stdDeviation="18" flood-color="#111730" flood-opacity="0.18"/>
    </filter>
  </defs>
  <image href="{$background}" x="0" y="0" width="1080" height="1350" preserveAspectRatio="xMidYMid slice"/>
SVG;

        if ($profilePhoto) {
            $svg .= <<<SVG
  <g filter="url(#attendee-photo-shadow)">
    <image href="{$profilePhoto}" x="92" y="548" width="404" height="404" preserveAspectRatio="xMidYMid slice" clip-path="url(#attendee-photo-clip)"/>
  </g>
SVG;
        } else {
            $initials = $this->initials($user->name);
            $svg .= <<<SVG
  <g filter="url(#attendee-photo-shadow)">
    <rect x="92" y="548" width="404" height="404" rx="52" fill="url(#fallback-gradient)"/>
    <text x="294" y="780" text-anchor="middle" font-size="150" font-weight="800" fill="#FFFFFF" font-family="'Avenir Next', Avenir, Montserrat, Arial, sans-serif">{$initials}</text>
  </g>
  <defs>
    <linearGradient id="fallback-gradient" x1="92" y1="548" x2="496" y2="952" gradientUnits="userSpaceOnUse">
      <stop stop-color="#9FD7CC"/>
      <stop offset="1" stop-color="#FF5B73"/>
    </linearGradient>
  </defs>
SVG;
        }

        $nameY = count($nameLines) === 1 ? 780 : 752;
        foreach ($nameLines as $index => $line) {
            $escapedLine = e($line);
            $lineY = $nameY + ($index * 58);
            $svg .= <<<SVG
  <text x="785" y="{$lineY}" text-anchor="middle" font-size="52" font-weight="800" fill="#FFFFFF" font-family="'Avenir Next', Avenir, Montserrat, Arial, sans-serif">{$escapedLine}</text>
SVG;
        }

        $svg .= "\n</svg>";

        return $svg;
    }

    public function speakerCaption(User $speaker, Session $session): string
    {
        $settings = AppSettings::all();
        $brandName = $settings['brand_name'] ?? 'this event';
        $venueName = $settings['venue_name'] ?? null;
        $registrationUrl = $settings['public_ticket_url'] ?? null;

        $caption = "I’m speaking at {$brandName}";

        if ($venueName) {
            $caption .= " at {$venueName}";
        }

        $caption .= ". Join me for \"{$session->title}\" on {$session->day->name} at {$this->formatTime($session->start_time)}.";

        if ($registrationUrl) {
            $caption .= " Register to join us at {$registrationUrl}.";
        }

        return $caption;
    }

    public function speakerSvg(User $speaker, Session $session): string
    {
        $background = $this->dataUri(public_path('share-cards/speaker-background.png'));
        $profilePhoto = $this->profilePhotoDataUri($speaker);
        $speakerNameLines = $this->wrapText($speaker->name, 18, 2);
        $titleLayout = $this->speakerTitleLayout($session->title);
        $titleLines = $titleLayout['lines'];
        $dateLabel = $session->day?->event_date ? Carbon::parse($session->day->event_date)->format('M j, Y') : null;
        $timeLabel = trim(collect([
            $this->formatTime($session->start_time),
            $this->formatTime($session->end_time),
        ])->filter()->implode(' - '));
        $detailLabel = trim(collect([
            $timeLabel,
            $dateLabel,
            $session->location,
        ])->filter()->implode('  |  '));
        $speakerNameY = count($speakerNameLines) === 1 ? 700 : 672;
        $titleX = 72;
        $titleY = $speakerNameY + (count($speakerNameLines) * 76) + 70;
        $titleBlockHeight = (max(count($titleLines), 1) - 1) * $titleLayout['lineHeight'];
        $detailY = min($titleY + $titleBlockHeight + 92, 1184);

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="1080" height="1350" viewBox="0 0 1080 1350" fill="none">
  <defs>
    <clipPath id="speaker-photo-clip">
      <circle cx="162" cy="488" r="118"/>
    </clipPath>
    <filter id="speaker-photo-shadow" x="28" y="354" width="268" height="268" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
      <feDropShadow dx="0" dy="14" stdDeviation="16" flood-color="#050B1C" flood-opacity="0.24"/>
    </filter>
    <linearGradient id="speaker-fallback-gradient" x1="44" y1="370" x2="280" y2="606" gradientUnits="userSpaceOnUse">
      <stop stop-color="#33466F"/>
      <stop offset="1" stop-color="#1B2A48"/>
    </linearGradient>
  </defs>
  <image href="{$background}" x="0" y="0" width="1080" height="1350" preserveAspectRatio="xMidYMid slice"/>
SVG;

        if ($profilePhoto) {
            $svg .= <<<SVG
  <g filter="url(#speaker-photo-shadow)">
    <image href="{$profilePhoto}" x="44" y="370" width="236" height="236" preserveAspectRatio="xMidYMid slice" clip-path="url(#speaker-photo-clip)"/>
  </g>
SVG;
        } else {
            $initials = $this->initials($speaker->name);
            $svg .= <<<SVG
  <g filter="url(#speaker-photo-shadow)">
    <circle cx="162" cy="488" r="118" fill="url(#speaker-fallback-gradient)"/>
    <text x="162" y="524" text-anchor="middle" font-size="88" font-weight="800" fill="#FFFFFF" font-family="'Avenir Next', Avenir, Montserrat, Arial, sans-serif">{$initials}</text>
  </g>
SVG;
        }

        foreach ($speakerNameLines as $index => $line) {
            $escapedLine = e($line);
            $lineY = $speakerNameY + ($index * 72);
            $svg .= <<<SVG
  <text x="72" y="{$lineY}" font-size="64" font-weight="800" fill="#FFFFFF" font-family="'Avenir Next', Avenir, Montserrat, Arial, sans-serif">{$escapedLine}</text>
SVG;
        }

        foreach ($titleLines as $index => $line) {
            $escapedLine = e($line);
            $lineY = $titleY + ($index * $titleLayout['lineHeight']);
            $fontSize = $titleLayout['fontSize'];
            $svg .= <<<SVG
  <text x="{$titleX}" y="{$lineY}" font-size="{$fontSize}" font-weight="500" fill="#FFFFFF" font-family="'Avenir Next', Avenir, Montserrat, Arial, sans-serif">{$escapedLine}</text>
SVG;
        }

        $escapedDetailLabel = e($detailLabel);
        $svg .= <<<SVG
  <text x="{$titleX}" y="{$detailY}" font-size="28" font-weight="500" fill="rgba(255,255,255,0.92)" font-family="'Avenir Next', Avenir, Montserrat, Arial, sans-serif">{$escapedDetailLabel}</text>
SVG;

        $svg .= "\n</svg>";

        return $svg;
    }

    protected function profilePhotoDataUri(User $user): ?string
    {
        if (! $user->profile_photo_path) {
            return null;
        }

        $fullPath = storage_path('app/public/'.$user->profile_photo_path);

        if (! is_file($fullPath) || ! is_readable($fullPath)) {
            return null;
        }

        return $this->dataUri($fullPath);
    }

    protected function dataUri(string $path): string
    {
        $mime = mime_content_type($path) ?: 'image/png';
        $contents = base64_encode(file_get_contents($path));

        return "data:{$mime};base64,{$contents}";
    }

    /**
     * @return array{lines: array<int, string>, fontSize: int, lineHeight: int}
     */
    protected function speakerTitleLayout(string $title): array
    {
        $length = mb_strlen(trim($title));

        if ($length >= 100) {
            return [
                'lines' => $this->wrapText($title, 31, 5),
                'fontSize' => 31,
                'lineHeight' => 38,
            ];
        }

        if ($length >= 76) {
            return [
                'lines' => $this->wrapText($title, 29, 4),
                'fontSize' => 33,
                'lineHeight' => 40,
            ];
        }

        return [
            'lines' => $this->wrapText($title, 27, 4),
            'fontSize' => 35,
            'lineHeight' => 42,
        ];
    }

    protected function formatTime(?string $time): string
    {
        if (! $time) {
            return 'TBD';
        }

        return Carbon::createFromFormat('H:i:s', $time)->format('g:i A');
    }

    protected function initials(string $name): string
    {
        return Str::of($name)
            ->explode(' ')
            ->filter()
            ->take(2)
            ->map(fn (string $part) => Str::upper(Str::substr($part, 0, 1)))
            ->implode('');
    }

    /**
     * @return array<int, string>
     */
    protected function wrapText(string $text, int $maxCharacters, int $maxLines = 2): array
    {
        $words = preg_split('/\s+/', trim($text)) ?: [];
        $lines = [];
        $current = '';

        foreach ($words as $word) {
            $candidate = trim($current.' '.$word);

            if ($current !== '' && mb_strlen($candidate) > $maxCharacters) {
                $lines[] = $current;
                $current = $word;

                if (count($lines) >= $maxLines - 1) {
                    break;
                }
            } else {
                $current = $candidate;
            }
        }

        if ($current !== '') {
            $lines[] = $current;
        }

        $lines = array_slice(array_values(array_filter($lines)), 0, $maxLines);

        if (count($lines) === $maxLines && count($words) > count(preg_split('/\s+/', implode(' ', $lines)))) {
            $lines[$maxLines - 1] = Str::limit($lines[$maxLines - 1], max(8, $maxCharacters - 1), '…');
        }

        if ($lines === [] && $text !== '') {
            return [Str::limit($text, $maxCharacters, '…')];
        }

        return $lines;
    }
}
