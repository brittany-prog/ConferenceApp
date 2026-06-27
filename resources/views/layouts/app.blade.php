<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $appSettings['brand_name'])</title>
    <meta name="theme-color" content="#12142d">
    <link rel="icon" type="image/png" href="{{ $appSettings['favicon_asset'] }}">
    <script>
        (() => {
            const storageKey = @json($appSettings['theme_storage_key']);
            const root = document.documentElement;
            const savedTheme = localStorage.getItem(storageKey);
            const activeTheme = savedTheme === 'light' || savedTheme === 'dark' ? savedTheme : 'light';
            root.dataset.theme = activeTheme;
        })();
    </script>
    <style>
        :root {
            --brand-primary: {{ $appSettings['brand_primary_color'] ?? '#ff4d6d' }};
            --brand-accent: {{ $appSettings['brand_accent_color'] ?? '#b8d8cf' }};
            --brand-surface: rgba(255, 255, 255, 0.96);
            --brand-surface-strong: #ffffff;
            --brand-ink: #10162f;
            --brand-muted: #42506c;
            --brand-line: rgba(23, 26, 55, 0.2);
            --brand-bg: #ffffff;
            --mobile-brand-bg: #ffffff;
            --brand-shadow: 0 24px 70px rgba(17, 23, 48, 0.12);
            --brand-link: #183153;
            --brand-link-hover: #0f2342;
            --nav-link-color: #25324f;
            --nav-link-hover: #13233e;
            --nav-link-current: #13233e;
            --nav-link-active-bg: rgba(19, 35, 62, 0.08);
            --nav-link-active-border: rgba(19, 35, 62, 0.12);
            --button-gradient: linear-gradient(135deg, #183153 0%, #355276 42%, #4f8f87 100%);
            --button-shadow: 0 16px 28px rgba(24, 49, 83, 0.2);
            --kicker-bg: rgba(184, 216, 207, 0.26);
            --kicker-color: #183153;
            --brand-hero-overlay: linear-gradient(135deg, rgba(16, 22, 47, 0.86), rgba(24, 49, 83, 0.72));
            --header-fallback-bg: radial-gradient(circle at top right, rgba(184, 216, 207, 0.22), transparent 28%),
                linear-gradient(225deg, #e8f1f4 0%, #deebf4 54%, #f3f8fb 100%);
            --header-image-overlay: linear-gradient(180deg, rgba(16, 22, 47, 0.22), rgba(16, 22, 47, 0.3));
            --nav-shell-bg: #ffffff;
            --nav-shell-border: rgba(23, 26, 55, 0.08);
            --header-brand-bg: #101a37;
            --header-brand-border: rgba(255, 248, 237, 0.12);
            --header-brand-heading: #fff9f1;
            --header-brand-copy: rgba(255, 247, 232, 0.78);
            --topbar-chip-bg: rgba(24, 49, 83, 0.08);
            --topbar-chip-color: #10162f;
            --topbar-chip-border: rgba(24, 49, 83, 0.16);
            --eyebrow-bg: rgba(184, 216, 207, 0.26);
            --eyebrow-color: #183153;
            --footer-copy-color: rgba(16, 22, 47, 0.8);
            --footer-link-color: rgba(16, 22, 47, 0.86);
            --grid-line-color: rgba(16, 22, 47, 0.04);
            --mobile-nav-backdrop: rgba(7, 11, 30, 0.42);
            --mobile-nav-panel-bg: #ffffff;
            --mobile-nav-panel-border: rgba(23, 26, 55, 0.08);
            --mobile-nav-group-bg: rgba(23, 26, 55, 0.04);
            --mobile-nav-group-border: rgba(23, 26, 55, 0.08);
            --mobile-nav-label: rgba(16, 22, 47, 0.72);
            --mobile-nav-title: var(--brand-ink);
            --mobile-nav-subtitle: rgba(16, 22, 47, 0.8);
            --mobile-nav-link-bg: rgba(255, 255, 255, 0.88);
            --mobile-nav-link-color: #10162f;
            --mobile-nav-link-border: rgba(16, 22, 47, 0.1);
            --panel-bg: #ffffff;
            --card-bg: #ffffff;
            --card-loud-bg:
                radial-gradient(circle at top left, rgba(184, 216, 207, 0.22), transparent 42%),
                radial-gradient(circle at bottom right, rgba(159, 215, 204, 0.16), transparent 46%),
                linear-gradient(180deg, #ffffff 0%, #fffdfa 100%);
            --table-wrap-bg: rgba(255, 255, 255, 0.92);
            --form-section-bg: rgba(255, 255, 255, 0.72);
            --surface-soft-bg: rgba(255, 255, 255, 0.84);
            --surface-soft-border: rgba(255, 255, 255, 0.28);
            --stat-bg: #ffffff;
            --meta-pill-bg: rgba(184, 216, 207, 0.24);
            --meta-pill-color: #183153;
            --secondary-button-bg: rgba(24, 49, 83, 0.07);
            --secondary-button-border: rgba(24, 49, 83, 0.16);
            --flash-success-bg: rgba(24, 102, 88, 0.92);
            --flash-success-color: #f4fffb;
            --flash-success-border: rgba(244, 255, 251, 0.18);
            --flash-error-bg: rgba(255, 77, 109, 0.14);
            --flash-error-color: #7d1734;
            --flash-error-border: rgba(125, 23, 52, 0.14);
            --input-bg: rgba(255, 255, 255, 0.88);
            --input-border: rgba(23, 26, 55, 0.28);
            --input-shadow: inset 0 0 0 1px rgba(23, 26, 55, 0.08);
            --theme-toggle-bg: #ffffff;
            --theme-toggle-border: rgba(16, 22, 47, 0.12);
            --theme-toggle-color: #10162f;
            --theme-toggle-active-bg: #10162f;
            --theme-toggle-active-color: #ffffff;
        }

        html[data-theme="dark"] {
            --brand-surface: rgba(17, 22, 46, 0.94);
            --brand-surface-strong: #171c39;
            --brand-ink: #171a37;
            --brand-muted: #4d5c79;
            --brand-line: rgba(244, 247, 255, 0.12);
            --brand-bg: #07101a;
            --mobile-brand-bg: #07101a;
            --brand-shadow: 0 24px 70px rgba(7, 11, 30, 0.34);
            --brand-link: #183153;
            --brand-link-hover: #0f2342;
            --nav-link-color: rgba(255, 248, 237, 0.88);
            --nav-link-hover: #fff8ed;
            --nav-link-current: #fff8ed;
            --nav-link-active-bg: rgba(255, 248, 237, 0.08);
            --nav-link-active-border: rgba(255, 248, 237, 0.12);
            --button-gradient: linear-gradient(135deg, #173257 0%, #2e507b 42%, #4a847d 100%);
            --button-shadow: 0 18px 30px rgba(11, 19, 34, 0.3);
            --kicker-bg: rgba(184, 216, 207, 0.18);
            --kicker-color: #183153;
            --brand-hero-overlay: linear-gradient(135deg, rgba(7, 11, 30, 0.9), rgba(16, 22, 47, 0.78));
            --header-fallback-bg: radial-gradient(circle at top left, rgba(184, 216, 207, 0.14), transparent 18%),
                radial-gradient(circle at top right, rgba(159, 215, 204, 0.14), transparent 22%),
                linear-gradient(225deg, #08111d 0%, #0b1523 52%, #0e1929 100%);
            --header-image-overlay: linear-gradient(180deg, rgba(7, 11, 30, 0.24), rgba(7, 11, 30, 0.58));
            --nav-shell-bg: #0c1630;
            --nav-shell-border: rgba(255, 248, 237, 0.12);
            --header-brand-bg: #101a37;
            --header-brand-border: rgba(255, 248, 237, 0.12);
            --header-brand-heading: #fff9f1;
            --header-brand-copy: rgba(255, 247, 232, 0.78);
            --topbar-chip-bg: rgba(255, 250, 239, 0.12);
            --topbar-chip-color: #fff8ed;
            --topbar-chip-border: rgba(255, 248, 237, 0.12);
            --eyebrow-bg: rgba(184, 216, 207, 0.18);
            --eyebrow-color: #d8eee8;
            --footer-copy-color: rgba(255, 247, 232, 0.78);
            --footer-link-color: rgba(255, 247, 232, 0.84);
            --grid-line-color: rgba(255, 255, 255, 0.025);
            --mobile-nav-backdrop: rgba(7, 11, 30, 0.56);
            --mobile-nav-panel-bg: #0c1630;
            --mobile-nav-panel-border: rgba(255, 248, 237, 0.12);
            --mobile-nav-group-bg: rgba(255, 250, 239, 0.08);
            --mobile-nav-group-border: rgba(255, 248, 237, 0.1);
            --mobile-nav-label: rgba(255, 247, 232, 0.72);
            --mobile-nav-title: #fff8ed;
            --mobile-nav-subtitle: rgba(255, 247, 232, 0.74);
            --mobile-nav-link-bg: rgba(255, 250, 239, 0.08);
            --mobile-nav-link-color: #fff8ed;
            --mobile-nav-link-border: rgba(255, 248, 237, 0.12);
            --panel-bg: #ffffff;
            --card-bg: #ffffff;
            --card-loud-bg: linear-gradient(180deg, #ffffff 0%, #ffffff 100%);
            --table-wrap-bg: rgba(255, 255, 255, 0.92);
            --form-section-bg: rgba(255, 255, 255, 0.72);
            --surface-soft-bg: rgba(255, 255, 255, 0.84);
            --surface-soft-border: rgba(255, 255, 255, 0.28);
            --stat-bg: #ffffff;
            --meta-pill-bg: rgba(184, 216, 207, 0.18);
            --meta-pill-color: #183153;
            --secondary-button-bg: rgba(184, 216, 207, 0.1);
            --secondary-button-border: rgba(184, 216, 207, 0.2);
            --flash-success-bg: rgba(19, 86, 74, 0.94);
            --flash-success-color: #f4fffb;
            --flash-success-border: rgba(244, 255, 251, 0.16);
            --flash-error-bg: rgba(255, 77, 109, 0.18);
            --flash-error-color: #ffe8ee;
            --flash-error-border: rgba(255, 232, 238, 0.14);
            --input-bg: rgba(255, 255, 255, 0.12);
            --input-border: rgba(255, 255, 255, 0.34);
            --input-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.14);
            --theme-toggle-bg: rgba(255, 248, 237, 0.08);
            --theme-toggle-border: rgba(255, 248, 237, 0.14);
            --theme-toggle-color: #fff8ed;
            --theme-toggle-active-bg: #fff8ed;
            --theme-toggle-active-color: #10162f;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Avenir Next", "Segoe UI", sans-serif;
            color: var(--brand-ink);
            background: var(--brand-bg);
            min-height: 100vh;
            position: relative;
        }
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background:
                linear-gradient(90deg, var(--grid-line-color) 1px, transparent 1px),
                linear-gradient(var(--grid-line-color) 1px, transparent 1px);
            background-size: 32px 32px;
            pointer-events: none;
            opacity: 0.35;
        }
        a {
            color: var(--brand-link);
            text-underline-offset: 0.18em;
        }
        a:hover {
            color: var(--brand-link-hover);
        }
        a:focus-visible,
        button:focus-visible,
        input:focus-visible,
        select:focus-visible,
        textarea:focus-visible {
            outline: 3px solid rgba(255, 77, 109, 0.28);
            outline-offset: 3px;
        }
        .shell {
            max-width: 1180px;
            margin: 0 auto;
            padding: 0 20px 72px;
            position: relative;
            z-index: 1;
        }
        .app-header-band {
            width: 100%;
            margin: 16px 0 16px;
            display: grid;
            gap: 12px;
        }
        .app-header-image {
            border-radius: 34px;
            overflow: hidden;
            border: 1px solid rgba(255, 248, 237, 0.12);
            box-shadow: 0 18px 44px rgba(7, 11, 30, 0.16);
            background: var(--header-fallback-bg);
            min-height: 168px;
            position: relative;
        }
        .app-header-image-inner {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: flex-end;
            min-height: 168px;
            max-width: none;
            margin: 0;
            padding: 24px 28px;
        }
        .app-header-image.has-image {
            background-size: cover;
            background-position: center center;
        }
        .app-header-image::before {
            content: "";
            position: absolute;
            inset: 0;
            background: var(--header-image-overlay);
        }
        .app-header-inner {
            position: relative;
            z-index: 1;
            max-width: none;
            margin: 0;
            padding: 0;
        }
        .app-nav-shell {
            border-radius: 24px;
            border: 1px solid var(--nav-shell-border);
            background: var(--nav-shell-bg);
            box-shadow: 0 14px 34px rgba(7, 11, 30, 0.16);
            overflow: hidden;
        }
        .header-brand {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 22px;
            background: var(--header-brand-bg);
            box-shadow: 0 12px 28px rgba(7, 11, 30, 0.14);
            border: 1px solid var(--header-brand-border);
        }
        .header-brand img,
        .header-brand .brand-mark {
            flex-shrink: 0;
        }
        .brand-media {
            position: relative;
            width: 48px;
            height: 48px;
            flex-shrink: 0;
        }
        .brand-media .brand-mark,
        .brand-media img {
            position: absolute;
            inset: 0;
        }
        .brand-media .brand-mark {
            display: none;
        }
        .brand-media.is-fallback .brand-mark {
            display: grid;
        }
        .brand-media.is-fallback img {
            display: none;
        }
        .header-brand h1 {
            font-size: clamp(1.15rem, 2vw, 1.45rem);
        }
        .header-brand p {
            max-width: 520px;
        }
        .topbar {
            display: grid;
            gap: 12px;
            padding: 12px 16px;
        }
        .topbar nav {
            width: auto;
        }
        .nav-header {
            display: none;
            width: 100%;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }
        .mobile-quick-actions {
            display: none;
            align-items: center;
            gap: 10px;
            padding: 6px 8px;
            border-radius: 18px;
            background: rgba(16, 22, 47, 0.04);
            border: 1px solid rgba(16, 22, 47, 0.08);
        }
        .nav-toggle {
            display: none;
            appearance: none;
            border: 1px solid var(--topbar-chip-border);
            background: var(--topbar-chip-bg);
            color: var(--topbar-chip-color);
            border-radius: 999px;
            padding: 10px 12px;
            font: inherit;
            font-weight: 700;
            line-height: 1;
            cursor: pointer;
            box-shadow: 0 10px 30px rgba(7, 11, 30, 0.14);
            gap: 8px;
        }
        .nav-toggle-label {
            display: inline-block;
        }
        .nav-toggle-icon {
            display: block;
            font-size: 1.2rem;
            line-height: 1;
        }
        .mobile-nav-close {
            appearance: none;
            border: 1px solid var(--theme-toggle-border);
            background: var(--theme-toggle-bg);
            color: var(--theme-toggle-color);
            border-radius: 14px;
            padding: 10px 12px;
            font: inherit;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: inherit;
        }
        .brand-mark {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            background: var(--button-gradient);
            display: grid;
            place-items: center;
            color: white;
            font-weight: 800;
            letter-spacing: 0.08em;
            box-shadow: var(--button-shadow);
        }
        .brand img {
            width: 48px;
            height: 48px;
            object-fit: contain;
            border-radius: 16px;
            background: transparent;
            box-shadow: 0 10px 28px rgba(11, 15, 37, 0.26);
        }
        .brand h1, .brand p {
            margin: 0;
        }
        .brand h1 {
            color: var(--header-brand-heading);
            letter-spacing: -0.03em;
        }
        .brand p {
            color: var(--header-brand-copy);
            font-size: 0.92rem;
        }
        .nav {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            align-items: center;
        }
        .desktop-nav {
            display: grid;
            width: 100%;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 14px;
            align-items: center;
        }
        .desktop-nav-primary,
        .desktop-nav-utility {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }
        .desktop-nav-utility {
            margin-left: auto;
            justify-content: flex-end;
            max-width: 100%;
        }
        .mobile-nav {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 40;
            background: rgba(7, 11, 30, 0.56);
            padding: 16px 12px;
            overflow-y: auto;
        }
        .nav a, .nav button {
            appearance: none;
            border: 0;
            background: rgba(23, 26, 55, 0.05);
            color: var(--brand-ink);
            border-radius: 999px;
            padding: 10px 14px;
            font-size: 0.95rem;
            text-decoration: none;
            cursor: pointer;
            box-shadow: 0 10px 24px rgba(7, 11, 30, 0.08);
            border: 1px solid rgba(23, 26, 55, 0.08);
        }
        .nav .primary {
            background: var(--button-gradient);
            color: white;
            font-weight: 700;
            border-color: transparent;
        }
        .topbar .nav a, .topbar .nav button {
            background: var(--topbar-chip-bg);
            color: var(--topbar-chip-color);
            box-shadow: 0 10px 30px rgba(7, 11, 30, 0.18);
            border: 1px solid var(--topbar-chip-border);
        }
        .topbar .desktop-nav-primary a {
            padding: 8px 4px;
            border-radius: 0;
            border: 0;
            background: transparent;
            box-shadow: none;
            font-weight: 650;
            letter-spacing: -0.01em;
            color: var(--nav-link-color);
        }
        .topbar .desktop-nav-primary a:hover {
            color: var(--nav-link-hover);
        }
        .topbar .desktop-nav-primary a.is-current {
            color: var(--nav-link-current);
            background: var(--nav-link-active-bg);
            border: 1px solid var(--nav-link-active-border);
            border-radius: 999px;
            padding: 8px 14px;
            text-decoration: none;
        }
        .topbar .desktop-nav-utility > a:not(.nav-icon-link):not(.primary),
        .topbar .desktop-nav-utility > form button {
            padding: 9px 14px;
            font-weight: 650;
        }
        .topbar .desktop-nav-utility > .primary,
        .topbar .desktop-nav-primary a {
            white-space: nowrap;
        }
        .mobile-nav-panel {
            display: grid;
            gap: 16px;
            width: 100%;
            max-width: 560px;
            margin: 0 auto;
            padding: 18px;
            border-radius: 28px;
            background: var(--mobile-nav-panel-bg);
            border: 1px solid var(--mobile-nav-panel-border);
            box-shadow: 0 28px 80px rgba(7, 11, 30, 0.4);
        }
        .mobile-nav-group {
            padding: 14px;
            border-radius: 18px;
            background: var(--mobile-nav-group-bg);
            border: 1px solid var(--mobile-nav-group-border);
        }
        .mobile-nav-label {
            display: block;
            margin-bottom: 10px;
            color: var(--mobile-nav-label);
            font-size: 0.76rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }
        .mobile-nav-links {
            display: grid;
            gap: 8px;
        }
        .mobile-nav-links a,
        .mobile-nav-links button {
            width: 100%;
            justify-content: flex-start;
            text-align: left;
            background: var(--mobile-nav-link-bg);
            color: var(--mobile-nav-link-color);
            border: 1px solid var(--mobile-nav-link-border);
            box-shadow: none;
        }
        .mobile-nav-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }
        .mobile-nav-title {
            color: var(--mobile-nav-title);
        }
        .mobile-nav-title strong,
        .mobile-nav-title span {
            display: block;
        }
        .mobile-nav-title span {
            margin-top: 2px;
            color: var(--mobile-nav-subtitle);
            font-size: 0.9rem;
        }
        .panel {
            background: var(--panel-bg);
            border: 1px solid var(--brand-line);
            border-radius: 24px;
            padding: 18px;
            box-shadow: 0 14px 32px rgba(17, 23, 48, 0.08);
            position: relative;
            overflow: hidden;
            color: var(--brand-ink);
        }
        .panel::after {
            content: "";
            position: absolute;
            inset: auto -8% -20% auto;
            width: 180px;
            height: 180px;
            border-radius: 999px;
            background: radial-gradient(circle, rgba(184, 216, 207, 0.16), transparent 68%);
            pointer-events: none;
        }
        .grid {
            display: grid;
            gap: 12px;
        }
        .grid-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
        .grid-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: var(--kicker-bg);
            color: var(--kicker-color);
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }
        .title {
            font-size: clamp(2rem, 5vw, 4rem);
            line-height: 0.95;
            margin: 18px 0 12px;
        }
        .muted {
            color: var(--brand-muted);
        }
        .lede {
            font-size: 1.02rem;
            line-height: 1.65;
            color: var(--brand-muted);
        }
        .subtle {
            font-size: 0.94rem;
            line-height: 1.55;
            color: var(--brand-muted);
        }
        .stack > * + * {
            margin-top: 12px;
        }
        .card {
            background: var(--card-bg);
            border: 1px solid var(--brand-line);
            border-radius: 18px;
            padding: 14px;
            box-shadow: 0 6px 18px rgba(15, 18, 42, 0.04);
            color: var(--brand-ink);
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            text-align: left;
            padding: 14px 12px;
            border-bottom: 1px solid var(--brand-line);
            vertical-align: top;
        }
        .table th {
            color: var(--brand-muted);
            font-size: 0.88rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
        .flash {
            padding: 14px 16px;
            border-radius: 18px;
            margin-bottom: 16px;
            border: 1px solid transparent;
        }
        .flash-success {
            background: var(--flash-success-bg);
            color: var(--flash-success-color);
            border-color: var(--flash-success-border);
        }
        .flash-error {
            background: var(--flash-error-bg);
            color: var(--flash-error-color);
            border-color: var(--flash-error-border);
        }
        label {
            display: block;
            font-weight: 700;
            margin-bottom: 6px;
        }
        input:not([type="file"]):not([type="checkbox"]):not([type="radio"]), select, textarea {
            width: 100%;
            border: 1px solid var(--input-border);
            border-radius: 14px;
            padding: 12px 14px;
            font: inherit;
            background: var(--input-bg);
            color: var(--brand-ink);
            box-shadow: var(--input-shadow);
            transition: border-color 160ms ease, box-shadow 160ms ease, background-color 160ms ease;
        }
        input[type="file"] {
            width: auto;
            max-width: 100%;
            border: 0;
            border-radius: 0;
            padding: 0;
            background: transparent;
            box-shadow: none;
            appearance: auto;
            -webkit-appearance: auto;
            display: block;
            cursor: pointer;
        }
        textarea {
            min-height: 120px;
            resize: vertical;
        }
        .field-help {
            margin-top: 8px;
            font-size: 0.92rem;
            line-height: 1.5;
            color: var(--brand-muted);
        }
        .check-row {
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }
        .check-row input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-top: 2px;
            accent-color: var(--brand-primary);
        }
        .check-row label {
            margin-bottom: 0;
            font-weight: 600;
        }
        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border-radius: 999px;
            padding: 12px 18px;
            text-decoration: none;
            font-weight: 700;
            border: 0;
            cursor: pointer;
            font: inherit;
        }
        .button.primary {
            background: var(--button-gradient);
            color: white;
            box-shadow: var(--button-shadow);
        }
        .button.secondary {
            background: var(--secondary-button-bg);
            color: var(--brand-ink);
            border: 1px solid var(--secondary-button-border);
        }
        .hero {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 28px;
            align-items: center;
        }
        .hero-visual {
            min-height: 360px;
            border-radius: 36px;
            background: linear-gradient(180deg, rgba(255,255,255,0.8), rgba(255,255,255,0.25)), linear-gradient(135deg, rgba(184,216,207,0.88), rgba(114,158,196,0.45), rgba(17,23,48,0.82));
            position: relative;
            overflow: hidden;
            box-shadow: var(--brand-shadow);
        }
        .hero-visual::before,
        .hero-visual::after {
            content: "";
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.18);
        }
        .hero-visual::before {
            width: 280px;
            height: 280px;
            top: -40px;
            right: -60px;
        }
        .hero-visual::after {
            width: 180px;
            height: 180px;
            bottom: -20px;
            left: 32px;
        }
        .hero-card {
            position: absolute;
            right: 28px;
            bottom: 28px;
            width: min(280px, 65%);
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.12);
            color: white;
            padding: 12px 14px;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,0.18);
            margin-top: 16px;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
        }
        .stats strong {
            display: block;
            font-size: 2rem;
            line-height: 1;
            margin-bottom: 8px;
            letter-spacing: -0.05em;
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1.45fr 0.95fr;
            gap: 26px;
            align-items: start;
        }
        .feature-list {
            display: grid;
            gap: 12px;
        }
        .feature-item {
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 12px;
            align-items: center;
            padding: 14px 16px;
            border-radius: 18px;
            background: rgba(255,255,255,0.72);
            border: 1px solid var(--brand-line);
        }
        .feature-item span:first-child {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            font-weight: 800;
            background: linear-gradient(145deg, rgba(24,49,83,0.95), rgba(79,143,135,0.94));
            color: white;
        }
        .feature-item h3,
        .feature-item p {
            margin: 0;
        }
        .feature-item p {
            color: var(--brand-muted);
            margin-top: 4px;
        }
        .section-heading {
            display: flex;
            justify-content: space-between;
            gap: 18px;
            align-items: end;
            flex-wrap: wrap;
        }
        .section-heading h2,
        .section-heading h3,
        .section-heading h4 {
            margin: 0;
            letter-spacing: -0.04em;
        }
        .card-loud {
            background: var(--card-loud-bg);
        }
        .list {
            display: grid;
            gap: 14px;
        }
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }
        .stat {
            padding: 18px;
            border-radius: 18px;
            background: var(--stat-bg);
            border: 1px solid var(--brand-line);
        }
        .stat small {
            display: block;
            font-size: 0.82rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--brand-muted);
            margin-bottom: 10px;
        }
        .stat strong {
            display: block;
            font-size: 2rem;
            letter-spacing: -0.05em;
        }
        .inline-form {
            display: inline;
        }
        .table-wrap {
            overflow-x: auto;
            border-radius: 22px;
            background: var(--table-wrap-bg);
            border: 1px solid var(--brand-line);
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.2);
        }
        .calendar {
            display: grid;
            gap: 12px;
        }
        .calendar-day {
            border-radius: 22px;
            border: 1px solid var(--brand-line);
            background: var(--card-bg);
            overflow: hidden;
        }
        .calendar-day h3 {
            margin: 0;
            padding: 18px 18px 0;
        }
        .calendar-items {
            display: grid;
            gap: 14px;
            padding: 18px;
        }
        .calendar-item {
            padding: 16px;
            border-radius: 18px;
            background: rgba(255,255,255,0.62);
            border: 1px solid var(--brand-line);
        }
        .calendar-item h4,
        .calendar-item p {
            margin: 0;
        }
        .calendar-item p + p {
            margin-top: 8px;
        }
        .calendar-item .meta {
            margin-top: 12px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            color: var(--brand-muted);
            font-size: 0.9rem;
        }
        .empty {
            padding: 24px;
            text-align: center;
            color: var(--brand-muted);
        }
        .profile-hero {
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 24px;
            align-items: center;
        }
        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 32px;
            object-fit: cover;
            background: rgba(255,255,255,0.65);
            border: 1px solid var(--brand-line);
            box-shadow: var(--brand-shadow);
        }
        .profile-avatar--fallback {
            display: grid;
            place-items: center;
            background: linear-gradient(145deg, rgba(24,49,83,0.92), rgba(79,143,135,0.9));
            color: white;
            font-size: 2.4rem;
            font-weight: 800;
            letter-spacing: -0.06em;
        }
        .profile-title {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }
        .profile-title h2 {
            margin: 0;
            letter-spacing: -0.05em;
        }
        .profile-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 14px;
            color: var(--brand-muted);
        }
        .pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(255,255,255,0.54);
            border: 1px solid var(--brand-line);
            color: inherit;
            font-size: 0.88rem;
            font-weight: 600;
        }
        .metric-number {
            font-size: 2.2rem;
            line-height: 1;
            letter-spacing: -0.06em;
            margin: 0;
        }
        .admin-edit-chip,
        .meta-stack {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        .meta-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 10px;
            border-radius: 999px;
            background: var(--meta-pill-bg);
            color: var(--meta-pill-color);
            font-size: 0.84rem;
            font-weight: 700;
            line-height: 1;
        }
        .admin-edit-chip {
            position: absolute;
            top: 16px;
            right: 16px;
            z-index: 2;
            align-items: center;
            padding: 9px 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.94);
            border: 1px solid rgba(16, 22, 47, 0.08);
            color: var(--brand-ink);
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 700;
            box-shadow: 0 10px 24px rgba(16, 22, 47, 0.12);
        }
        .nav-icon-link {
            position: relative;
            width: 42px;
            height: 42px;
            padding: 0;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .nav-icon-link svg {
            width: 20px;
            height: 20px;
            stroke: currentColor;
        }
        .nav-link-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            min-width: 18px;
            height: 18px;
            padding: 0 5px;
            border-radius: 999px;
            background: #ff4d6d;
            color: #fff;
            font-size: 0.72rem;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 18px rgba(255, 77, 109, 0.32);
        }
        .theme-toggle-group {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px;
            border-radius: 999px;
            background: var(--theme-toggle-bg);
            border: 1px solid var(--theme-toggle-border);
            box-shadow: 0 10px 24px rgba(7, 11, 30, 0.08);
        }
        .theme-toggle-button {
            appearance: none;
            border: 0;
            background: transparent;
            color: var(--theme-toggle-color);
            padding: 8px 12px;
            border-radius: 999px;
            font: inherit;
            font-size: 0.86rem;
            font-weight: 700;
            line-height: 1;
            cursor: pointer;
            transition: background-color 0.18s ease, color 0.18s ease, box-shadow 0.18s ease;
        }
        .theme-toggle-button.is-active {
            background: var(--theme-toggle-active-bg);
            color: var(--theme-toggle-active-color);
            box-shadow: 0 8px 18px rgba(7, 11, 30, 0.16);
        }
        .desktop-theme-toggle {
            flex: 0 0 auto;
        }
        .mobile-stack {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }
        .mobile-stack > * {
            flex: 1 1 220px;
        }
        .section-tabs {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.72);
            border: 1px solid var(--brand-line);
            box-shadow: 0 10px 22px rgba(17, 23, 48, 0.07);
            flex-wrap: wrap;
        }
        .section-tab {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 36px;
            padding: 8px 12px;
            border-radius: 999px;
            color: var(--brand-muted);
            text-decoration: none;
            font-weight: 700;
            line-height: 1;
        }
        .section-tab.is-current {
            background: var(--button-gradient);
            color: #fff;
            box-shadow: var(--button-shadow);
        }
        .dark-page-shell {
            display: grid;
            gap: 14px;
        }
        .mobile-bottom-nav {
            display: none;
        }
        .app-footer {
            margin-top: 32px;
            padding: 8px 2px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }
        .app-footer-copy {
            color: var(--footer-copy-color);
            font-size: 0.92rem;
            font-weight: 400;
        }
        .app-footer-brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--footer-link-color);
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 400;
        }
        .app-footer-brand img {
            height: 16px;
            max-width: 120px;
            width: auto;
            display: block;
            object-fit: contain;
            flex-shrink: 0;
        }
        .app-footer-brand:hover {
            color: var(--brand-link);
        }
        html[data-theme="dark"] .dark-page-shell {
            padding: 18px;
            border-radius: 34px;
            background: #0c1630;
            border: 1px solid rgba(255, 248, 237, 0.08);
            box-shadow: 0 24px 64px rgba(0, 0, 0, 0.28);
        }
        @media (min-width: 1100px) {
            .topbar {
                padding: 16px 22px;
            }

            .desktop-nav-primary {
                gap: 14px;
            }

            .desktop-nav-utility {
                gap: 12px;
            }
        }
        @media (max-width: 800px) {
            .hero-grid, .stat-row,
            .grid-3, .grid-2 {
                grid-template-columns: 1fr;
            }
            .shell {
                padding: 0 12px 108px;
            }
            .app-header-band {
                width: 100vw;
                margin: 12px calc(50% - 50vw) 14px;
            }
            .app-header-inner {
                padding: 0;
            }
            .app-header-image {
                border-radius: 0;
                border-left: 0;
                border-right: 0;
                min-height: 132px;
            }
            body {
                background: var(--mobile-brand-bg);
            }
            .app-header-image-inner {
                min-height: 132px;
                padding: 16px 14px;
                align-items: center;
            }
            .header-brand {
                width: min(100%, 100%);
                gap: 12px;
                padding: 10px 12px;
                border-radius: 18px;
            }
            .header-brand p {
                max-width: 180px;
                font-size: 0.84rem;
            }
            .topbar {
                padding: 10px 14px;
                gap: 10px;
            }
            .nav-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
            }
            .mobile-quick-actions {
                display: inline-flex;
            }
            .mobile-quick-actions .nav-icon-link {
                width: 38px;
                height: 38px;
                border-radius: 12px;
                background: var(--topbar-chip-bg) !important;
                color: var(--topbar-chip-color) !important;
                border: 1px solid var(--topbar-chip-border) !important;
                box-shadow: none !important;
            }
            .mobile-quick-actions .nav-icon-link svg {
                width: 18px;
                height: 18px;
            }
            html[data-theme="dark"] .mobile-quick-actions .nav-icon-link {
                background: rgba(255, 248, 237, 0.08) !important;
                color: #fff8ed !important;
                border-color: rgba(255, 248, 237, 0.14) !important;
            }
            .nav-toggle {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                justify-content: center;
                padding: 10px 12px;
            }
            .nav-toggle-label {
                font-size: 0.95rem;
            }
            .desktop-nav {
                display: none;
            }
            .mobile-nav.is-open {
                display: block;
            }
            .mobile-nav-panel {
                gap: 14px;
            }
            .desktop-theme-toggle {
                display: none;
            }
            .stats,
            .stat-grid,
            .hero,
            .dashboard-grid,
            .grid-2,
            .grid-3,
            .profile-hero {
                grid-template-columns: 1fr;
            }
            .feature-item {
                grid-template-columns: auto 1fr;
            }
            .section-heading {
                gap: 12px;
                align-items: flex-start;
            }
            .section-tabs {
                width: 100%;
                justify-content: flex-start;
            }
            .section-tab {
                flex: 1 1 0;
                min-width: 0;
                padding: 10px 12px;
                font-size: 0.9rem;
            }
            .mobile-bottom-nav {
                position: fixed;
                left: 12px;
                right: 12px;
                bottom: 12px;
                z-index: 45;
                display: grid;
                grid-template-columns: repeat(5, minmax(0, 1fr));
                gap: 8px;
                padding: 10px;
                border-radius: 24px;
                background: rgba(255, 255, 255, 0.92);
                border: 1px solid rgba(23, 26, 55, 0.08);
                box-shadow: 0 18px 40px rgba(7, 11, 30, 0.22);
                backdrop-filter: blur(18px);
            }
            html[data-theme="dark"] .mobile-bottom-nav {
                background: #0c1630;
                border-color: rgba(255, 248, 237, 0.1);
                box-shadow: 0 18px 44px rgba(0, 0, 0, 0.36);
            }
            .mobile-bottom-nav__link {
                display: grid;
                justify-items: center;
                gap: 6px;
                padding: 8px 4px;
                border-radius: 18px;
                color: var(--brand-muted);
                text-decoration: none;
                font-size: 0.72rem;
                font-weight: 700;
                line-height: 1.1;
                text-align: center;
                position: relative;
            }
            .mobile-bottom-nav__link svg {
                width: 20px;
                height: 20px;
                stroke: currentColor;
            }
            .mobile-bottom-nav__link.is-current {
                background: rgba(24, 49, 83, 0.08);
                color: var(--brand-ink);
            }
            html[data-theme="dark"] .mobile-bottom-nav__link {
                color: #ffffff;
            }
            html[data-theme="dark"] .mobile-bottom-nav__link.is-current {
                background: rgba(255, 248, 237, 0.12);
                color: #ffffff;
            }
            .mobile-bottom-nav__badge {
                position: absolute;
                top: 2px;
                right: 16%;
                min-width: 16px;
                height: 16px;
                padding: 0 4px;
                border-radius: 999px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                background: #ff4d6d;
                color: #fff;
                font-size: 0.65rem;
                font-weight: 800;
            }
            html[data-theme="dark"] .dark-page-shell {
                padding: 12px;
                border-radius: 24px;
            }
        }
        @media (max-width: 520px) {
            .brand-mark, .brand img {
                width: 42px;
                height: 42px;
                border-radius: 14px;
            }
            .brand h1 {
                font-size: 1rem;
            }
            .nav a, .nav button {
                padding: 10px 12px;
                font-size: 0.9rem;
            }
            .mobile-nav {
                padding: 10px;
            }
            .mobile-nav-panel {
                padding: 14px;
                border-radius: 22px;
            }
            .nav-toggle {
                padding: 10px;
            }
            .admin-edit-chip {
                top: 14px;
                right: 14px;
                padding: 9px 12px;
            }
        }
    </style>
    @stack('page-styles')
</head>
<body>
    <div class="shell">
        @php($globalHeaderImagePath = $appSettings['header_image_path'] ?? $appSettings['dashboard_cover_image_path'] ?? null)
        @php($brandLogoSource = $appSettings['brand_logo_asset'] ?? null)
        @php($publicRegistrationUrl = $appSettings['public_ticket_url'] ?? null)
        <section class="app-header-band">
            <div class="app-header-image @if(!empty($globalHeaderImagePath)) has-image @endif" @if(!empty($globalHeaderImagePath)) style="background-image:url('{{ asset('storage/'.$globalHeaderImagePath) }}');" @endif>
                <div class="app-header-image-inner">
                    <a class="brand header-brand" href="{{ auth()->check() ? '/app' : '/' }}">
                        <div class="brand-media @if (empty($brandLogoSource)) is-fallback @endif">
                            @if (!empty($brandLogoSource))
                                <img src="{{ $brandLogoSource }}" alt="{{ $appSettings['brand_name'] }} logo" onerror="this.closest('.brand-media').classList.add('is-fallback')">
                            @endif
                            <div class="brand-mark">{{ strtoupper(substr($appSettings['brand_name'], 0, 2)) }}</div>
                        </div>
                        <div>
                            <h1>{{ $appSettings['brand_name'] }}</h1>
                            <p>{{ $appSettings['brand_tagline'] }}</p>
                        </div>
                    </a>
                </div>
            </div>
            <div class="app-header-inner">
                <div class="app-nav-shell">
                        <header class="topbar">
                            <div class="nav-header">
                                <div class="mobile-quick-actions" aria-label="Quick actions">
                                    <a href="{{ auth()->check() ? '/app' : '/' }}" class="nav-icon-link" aria-label="Home">
                                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                            <path d="M4 10.5L12 4L20 10.5V19C20 19.5523 19.5523 20 19 20H5C4.44772 20 4 19.5523 4 19V10.5Z" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M9 20V13H15V20" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </a>
                                    @auth
                                        <a href="/notifications" class="nav-icon-link" aria-label="Notifications">
                                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                <path d="M15 18H9M18 16V11C18 7.68629 15.3137 5 12 5C8.68629 5 6 7.68629 6 11V16L4 18V19H20V18L18 16Z" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            @if(($notificationsCount ?? 0) > 0)<span class="nav-link-badge">{{ $notificationsCount }}</span>@endif
                                        </a>
                                        <a href="/messages" class="nav-icon-link" aria-label="Messages">
                                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                <path d="M4 6.5C4 5.67157 4.67157 5 5.5 5H18.5C19.3284 5 20 5.67157 20 6.5V15.5C20 16.3284 19.3284 17 18.5 17H9L4 20V6.5Z" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            @if(($unreadMessagesCount ?? 0) > 0)<span class="nav-link-badge">{{ $unreadMessagesCount }}</span>@endif
                                        </a>
                                    @endauth
                                </div>
                                <button type="button" class="nav-toggle" aria-expanded="false" aria-controls="app-nav" aria-label="Open menu">
                                    <span class="nav-toggle-label">Menu</span>
                                    <span class="nav-toggle-icon" aria-hidden="true">&#9776;</span>
                                </button>
                            </div>

                        <nav class="nav desktop-nav" aria-label="Primary">
                            <div class="desktop-nav-primary">
                                <a href="{{ auth()->check() ? '/app' : '/' }}" @class(['is-current' => request()->path() === '/' || request()->is('app')])>Home</a>
                                @auth
                                    <a href="/sessions" @class(['is-current' => request()->is('sessions') || request()->is('sessions/*')])>Sessions</a>
                                    <a href="/venue" @class(['is-current' => request()->is('venue')])>Venue</a>
                                    <a href="/community" @class(['is-current' => request()->is('community') || request()->is('community/*')])>Community</a>
                                    <a href="/speakers" @class(['is-current' => request()->is('speakers') || request()->is('speakers/*')])>Speakers</a>
                                    <a href="/attendees" @class(['is-current' => request()->is('attendees') || request()->is('attendees/*')])>Attendees</a>
                                    <a href="/sponsors" @class(['is-current' => request()->is('sponsors') || request()->is('sponsors/*')])>Sponsors & Exhibitors</a>
                                    <a href="/announcements" @class(['is-current' => request()->is('announcements')])>Announcements</a>
                                @else
                                    <a href="/register" @class(['is-current' => request()->is('register')])>Register</a>
                                    @if (!empty($publicRegistrationUrl))
                                        <a href="{{ $publicRegistrationUrl }}" target="_blank" rel="noreferrer">{{ $appSettings['public_ticket_label'] ?: 'Get Tickets' }}</a>
                                    @endif
                                @endauth
                            </div>

                            <div class="desktop-nav-utility">
                                @auth
                                    <a href="/my-schedule" class="primary">My Schedule</a>
                                    <a href="/notifications" class="nav-icon-link" aria-label="Notifications">
                                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                            <path d="M15 18H9M18 16V11C18 7.68629 15.3137 5 12 5C8.68629 5 6 7.68629 6 11V16L4 18V19H20V18L18 16Z" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        @if(($notificationsCount ?? 0) > 0)<span class="nav-link-badge">{{ $notificationsCount }}</span>@endif
                                    </a>
                                    <a href="/messages" class="nav-icon-link" aria-label="Messages">
                                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                            <path d="M4 6.5C4 5.67157 4.67157 5 5.5 5H18.5C19.3284 5 20 5.67157 20 6.5V15.5C20 16.3284 19.3284 17 18.5 17H9L4 20V6.5Z" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        @if(($unreadMessagesCount ?? 0) > 0)<span class="nav-link-badge">{{ $unreadMessagesCount }}</span>@endif
                                    </a>
                                    <a href="/profile" class="nav-icon-link" aria-label="Profile">
                                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                            <path d="M12 12C14.2091 12 16 10.2091 16 8C16 5.79086 14.2091 4 12 4C9.79086 4 8 5.79086 8 8C8 10.2091 9.79086 12 12 12Z" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M5 20C5.85033 17.1085 8.5785 15 12 15C15.4215 15 18.1497 17.1085 19 20" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </a>
                                    <div class="theme-toggle-group desktop-theme-toggle" aria-label="Theme toggle">
                                        <button type="button" class="theme-toggle-button" data-theme-value="light">Light</button>
                                        <button type="button" class="theme-toggle-button" data-theme-value="dark">Dark</button>
                                    </div>
                                    @if ((int) auth()->user()->is_admin === 1 || auth()->user()->is_admin === true)
                                        <a href="/admin">Admin</a>
                                    @endif
                                    <form method="POST" action="/logout" class="inline-form">
                                        @csrf
                                        <button type="submit">Logout</button>
                                    </form>
                                @else
                                    <div class="theme-toggle-group desktop-theme-toggle" aria-label="Theme toggle">
                                        <button type="button" class="theme-toggle-button" data-theme-value="light">Light</button>
                                        <button type="button" class="theme-toggle-button" data-theme-value="dark">Dark</button>
                                    </div>
                                    <a href="/login" class="primary">Login to Enter</a>
                                @endauth
                            </div>
                        </nav>

                        <nav id="app-nav" class="mobile-nav" aria-label="Mobile">
                            <div class="mobile-nav-panel">
                                <div class="mobile-nav-topbar">
                                    <div class="mobile-nav-title">
                                        <strong>Menu</strong>
                                        <span>{{ $appSettings['brand_name'] }}</span>
                                    </div>
                                    <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap; justify-content:flex-end;">
                                        <div class="theme-toggle-group" aria-label="Theme toggle">
                                            <button type="button" class="theme-toggle-button" data-theme-value="light">Light</button>
                                            <button type="button" class="theme-toggle-button" data-theme-value="dark">Dark</button>
                                        </div>
                                        <button type="button" class="mobile-nav-close" aria-label="Close menu">Close</button>
                                    </div>
                                </div>
                                @auth
                                    <div class="mobile-nav-group">
                                        <span class="mobile-nav-label">Plan</span>
                                        <div class="mobile-nav-links nav">
                                            <a href="/venue">Venue</a>
                                            <a href="/my-schedule">My Schedule</a>
                                            <a href="/announcements">Announcements</a>
                                        </div>
                                    </div>

                                    <div class="mobile-nav-group">
                                        <span class="mobile-nav-label">People & Updates</span>
                                        <div class="mobile-nav-links nav">
                                            <a href="/speakers">Speakers</a>
                                            <a href="/sponsors">Sponsors & Exhibitors</a>
                                            <a href="/notifications">Notifications @if(($notificationsCount ?? 0) > 0)<span class="nav-link-badge">{{ $notificationsCount }}</span>@endif</a>
                                        </div>
                                    </div>

                                    <div class="mobile-nav-group">
                                        <span class="mobile-nav-label">Account</span>
                                        <div class="mobile-nav-links nav">
                                            <a href="/profile">Profile</a>
                                            @if ((int) auth()->user()->is_admin === 1 || auth()->user()->is_admin === true)
                                                <a href="/admin" class="primary">Admin</a>
                                            @endif
                                            <form method="POST" action="/logout" class="inline-form">
                                                @csrf
                                                <button type="submit">Logout</button>
                                            </form>
                                        </div>
                                    </div>
                                @else
                                    <div class="mobile-nav-group">
                                        <span class="mobile-nav-label">Welcome</span>
                                        <div class="mobile-nav-links nav">
                                            <a href="/" class="button secondary">Home</a>
                                            <a href="/register">Register for the App</a>
                                            @if (!empty($publicRegistrationUrl))
                                                <a href="{{ $publicRegistrationUrl }}" target="_blank" rel="noreferrer">{{ $appSettings['public_ticket_label'] ?: 'Get Tickets' }}</a>
                                            @endif
                                            <a href="/login" class="primary">Login to Enter</a>
                                        </div>
                                    </div>
                                @endauth
                            </div>
                        </nav>
                    </header>
                </div>
            </div>
        </section>

        @if (session('success'))
            <div class="flash flash-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="flash flash-error">
                {{ $errors->first() }}
            </div>
        @endif

        @auth
            @if (! request()->is('notifications') && ($notificationPreview ?? collect())->isNotEmpty())
                <section class="notification-strip">
                    @foreach ($notificationPreview as $notification)
                        <a href="{{ $notification['link'] }}" class="card" style="display:flex; justify-content:space-between; gap:14px; align-items:flex-start; text-decoration:none; color:inherit;">
                            <div>
                                <span class="eyebrow">{{ ucfirst($notification['type']) }}</span>
                                <p style="margin:12px 0 6px;"><strong>{{ $notification['title'] }}</strong></p>
                                <p class="muted" style="margin:0;">{{ $notification['body'] }}</p>
                            </div>
                            <p class="muted" style="margin:0; white-space:nowrap;">{{ $notification['timestamp']?->diffForHumans() }}</p>
                        </a>
                    @endforeach
                </section>
            @endif
        @endauth

        @yield('content')

        <footer class="app-footer">
            <div class="app-footer-copy">
                &copy; {{ $appSettings['footer_copyright'] ?: trim(collect([$appSettings['brand_name'], $appSettings['event_date_range_label'] ? \Illuminate\Support\Carbon::parse($appSettings['event_start_date'] ?? now())->format('Y') : null])->filter()->implode(' ')) }}
            </div>
            @if (!empty($appSettings['footer_powered_by_url']))
                <a href="{{ $appSettings['footer_powered_by_url'] }}" target="_blank" rel="noreferrer" class="app-footer-brand">
                    <img src="{{ asset('red-beans-group-logo.png') }}" alt="Partner logo">
                    <span>{{ $appSettings['footer_powered_by_label'] ?: 'Powered by partner' }}</span>
                </a>
            @endif
        </footer>
    </div>
    @auth
        <nav class="mobile-bottom-nav" aria-label="Primary mobile navigation">
            <a href="/app" @class(['mobile-bottom-nav__link', 'is-current' => request()->path() === '/' || request()->is('app')])>
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M4 10.5L12 4L20 10.5V19C20 19.5523 19.5523 20 19 20H5C4.44772 20 4 19.5523 4 19V10.5Z" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M9 20V13H15V20" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>Home</span>
            </a>
            <a href="/sessions" @class(['mobile-bottom-nav__link', 'is-current' => request()->is('sessions') || request()->is('sessions/*') || request()->is('my-schedule')])>
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M8 3V6" stroke-width="1.8" stroke-linecap="round"/>
                    <path d="M16 3V6" stroke-width="1.8" stroke-linecap="round"/>
                    <rect x="4" y="5" width="16" height="15" rx="2.5" stroke-width="1.8"/>
                    <path d="M4 9H20" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
                <span>Agenda</span>
            </a>
            <a href="/attendees" @class(['mobile-bottom-nav__link', 'is-current' => request()->is('attendees') || request()->is('attendees/*') || request()->is('speakers') || request()->is('speakers/*') || request()->is('sponsors') || request()->is('sponsors/*')])>
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M16 19C16 16.7909 14.2091 15 12 15H8C5.79086 15 4 16.7909 4 19" stroke-width="1.8" stroke-linecap="round"/>
                    <path d="M10 11C11.6569 11 13 9.65685 13 8C13 6.34315 11.6569 5 10 5C8.34315 5 7 6.34315 7 8C7 9.65685 8.34315 11 10 11Z" stroke-width="1.8"/>
                    <path d="M17 11C18.1046 11 19 10.1046 19 9C19 7.89543 18.1046 7 17 7" stroke-width="1.8" stroke-linecap="round"/>
                    <path d="M20 19C20 17.3431 18.6569 16 17 16" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
                <span>People</span>
            </a>
            <a href="/community" @class(['mobile-bottom-nav__link', 'is-current' => request()->is('community') || request()->is('community/*')])>
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M4 6.5C4 5.67157 4.67157 5 5.5 5H18.5C19.3284 5 20 5.67157 20 6.5V15.5C20 16.3284 19.3284 17 18.5 17H9L4 20V6.5Z" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>Community</span>
            </a>
            <a href="/messages" @class(['mobile-bottom-nav__link', 'is-current' => request()->is('messages') || request()->is('messages/*') || request()->is('notifications')])>
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M4 6.5C4 5.67157 4.67157 5 5.5 5H18.5C19.3284 5 20 5.67157 20 6.5V15.5C20 16.3284 19.3284 17 18.5 17H9L4 20V6.5Z" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M8 9H16" stroke-width="1.8" stroke-linecap="round"/>
                    <path d="M8 12H13" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
                <span>Inbox</span>
                @if((($unreadMessagesCount ?? 0) + ($notificationsCount ?? 0)) > 0)
                    <span class="mobile-bottom-nav__badge">{{ ($unreadMessagesCount ?? 0) + ($notificationsCount ?? 0) }}</span>
                @endif
            </a>
        </nav>
    @endauth
    @stack('page-scripts')
    <script>
        (() => {
            const themeStorageKey = @json($appSettings['theme_storage_key']);
            const root = document.documentElement;
            const toggle = document.querySelector('.nav-toggle');
            const nav = document.querySelector('#app-nav');
            const close = document.querySelector('.mobile-nav-close');
            const themeButtons = Array.from(document.querySelectorAll('.theme-toggle-button'));
            const themeColor = document.querySelector('meta[name="theme-color"]');

            const updateThemeUi = (theme) => {
                themeButtons.forEach((button) => {
                    button.classList.toggle('is-active', button.dataset.themeValue === theme);
                    button.setAttribute('aria-pressed', button.dataset.themeValue === theme ? 'true' : 'false');
                });

                if (themeColor) {
                    themeColor.setAttribute('content', theme === 'light' ? '#edf2fb' : '#12142d');
                }
            };

            const applyTheme = (theme) => {
                root.dataset.theme = theme;
                localStorage.setItem(themeStorageKey, theme);
                updateThemeUi(theme);
            };

            if (!toggle || !nav) {
                updateThemeUi(root.dataset.theme || 'dark');
                return;
            }

            const closeNav = () => {
                nav.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
                document.body.style.overflow = '';
            };

            const syncNavState = () => {
                if (window.innerWidth > 800) {
                    closeNav();
                }
            };

            toggle.addEventListener('click', () => {
                const isOpen = nav.classList.toggle('is-open');
                toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                document.body.style.overflow = isOpen ? 'hidden' : '';
            });

            themeButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    applyTheme(button.dataset.themeValue);
                });
            });

            close?.addEventListener('click', closeNav);

            nav.addEventListener('click', (event) => {
                if (event.target === nav) {
                    closeNav();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeNav();
                }
            });

            window.addEventListener('resize', syncNavState);
            syncNavState();
            updateThemeUi(root.dataset.theme || 'dark');
        })();
    </script>
</body>
</html>
