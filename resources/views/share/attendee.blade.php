@extends('layouts.app')

@section('title', 'Share I’m Attending | '.$appSettings['brand_name'])

@section('content')
    <section class="panel stack" style="max-width: 1100px; margin: 0 auto;">
        <div class="section-heading">
            <div>
                <span class="eyebrow">Share Card</span>
                <h2 style="margin: 10px 0 0;">Share that you’re attending</h2>
                <p class="muted" style="max-width: 640px;">Download your attendee card and copy a caption you can use on LinkedIn, Instagram, or anywhere else you want to spread the word.</p>
            </div>
            <a href="/app" class="button secondary">Back to dashboard</a>
        </div>

        <div class="grid grid-2 share-card-layout" style="align-items:start;">
            <div class="card card-loud share-card-preview">
                <img src="{{ $cardUrl }}" alt="Your {{ $appSettings['brand_name'] }} attendee share card preview">
            </div>

            <div class="stack">
                <div class="card card-loud share-card-actions">
                    <p class="muted" style="margin:0 0 8px;">Download</p>
                    <h3 style="margin:0 0 10px;">Your attendee card is ready</h3>
                    <p class="muted" style="margin:0 0 16px;">Use the download button to save the image first, then attach it to your social post.</p>
                    <div class="share-card-action-row">
                        <button type="button" class="button" data-download-svg="{{ $downloadUrl }}" data-download-filename="{{ $appSettings['brand_slug'] }}-attendance.png">Download PNG card</button>
                    </div>
                </div>

                <div class="card card-loud share-card-actions">
                    <p class="muted" style="margin:0 0 8px;">Caption</p>
                    <h3 style="margin:0 0 10px;">Suggested social copy</h3>
                    <textarea id="attendee-share-caption" rows="6" readonly>{{ $caption }}</textarea>
                    <div class="share-card-action-row" style="margin-top: 14px;">
                        <button type="button" class="button secondary" data-copy-target="attendee-share-caption">Copy caption</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('page-styles')
    <style>
        .share-card-preview {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .share-card-preview img {
            width: 100%;
            max-width: 540px;
            height: auto;
            border-radius: 28px;
            box-shadow: 0 22px 48px rgba(17, 23, 48, 0.2);
        }

        .share-card-actions textarea {
            min-height: 160px;
            resize: vertical;
        }

        .share-card-action-row {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        @media (max-width: 720px) {
            .share-card-layout {
                grid-template-columns: 1fr !important;
            }

            .share-card-preview {
                padding: 18px;
            }

            .share-card-action-row .button {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
@endpush

@push('page-scripts')
    <script>
        const downloadSvgAsPng = async (url, filename, button) => {
            const response = await fetch(url);
            const svgText = await response.text();
            const svgBlob = new Blob([svgText], { type: 'image/svg+xml;charset=utf-8' });
            const svgBlobUrl = URL.createObjectURL(svgBlob);

            try {
                const image = await new Promise((resolve, reject) => {
                    const img = new Image();
                    img.onload = () => resolve(img);
                    img.onerror = reject;
                    img.src = svgBlobUrl;
                });

                const canvas = document.createElement('canvas');
                canvas.width = 1080;
                canvas.height = 1350;
                const context = canvas.getContext('2d');
                context.drawImage(image, 0, 0, canvas.width, canvas.height);

                const pngBlob = await new Promise((resolve) => canvas.toBlob(resolve, 'image/png'));
                const pngUrl = URL.createObjectURL(pngBlob);
                const link = document.createElement('a');
                link.href = pngUrl;
                link.download = filename;
                link.click();
                URL.revokeObjectURL(pngUrl);

                if (button) {
                    button.textContent = 'Downloaded';
                    setTimeout(() => {
                        button.textContent = 'Download PNG card';
                    }, 1800);
                }
            } finally {
                URL.revokeObjectURL(svgBlobUrl);
            }
        };

        document.querySelectorAll('[data-copy-target]').forEach((button) => {
            button.addEventListener('click', async () => {
                const target = document.getElementById(button.getAttribute('data-copy-target'));

                if (!target) {
                    return;
                }

                try {
                    await navigator.clipboard.writeText(target.value);
                    button.textContent = 'Copied';
                    setTimeout(() => {
                        button.textContent = 'Copy caption';
                    }, 1800);
                } catch (error) {
                    target.focus();
                    target.select();
                    document.execCommand('copy');
                    button.textContent = 'Copied';
                    setTimeout(() => {
                        button.textContent = 'Copy caption';
                    }, 1800);
                }
            });
        });

        document.querySelectorAll('[data-download-svg]').forEach((button) => {
            button.addEventListener('click', async () => {
                try {
                    await downloadSvgAsPng(button.getAttribute('data-download-svg'), button.getAttribute('data-download-filename') || 'share-card.png', button);
                } catch (error) {
                    button.textContent = 'Try again';
                    setTimeout(() => {
                        button.textContent = 'Download PNG card';
                    }, 1800);
                }
            });
        });
    </script>
@endpush
