@extends('layouts.app')

@section('title', 'Promote This Session | '.$appSettings['brand_name'])

@section('content')
    <section class="panel stack" style="max-width: 1100px; margin: 0 auto;">
        <div class="section-heading">
            <div>
                <span class="eyebrow">Share Card</span>
                <h2 style="margin: 10px 0 0;">Promote your session</h2>
                <p class="muted" style="max-width: 680px;">Download a branded speaker graphic for <strong>{{ $session->title }}</strong> and copy a caption you can use on social to invite people to your session.</p>
            </div>
            <a href="/sessions/{{ $session->id }}" class="button secondary">Back to session</a>
        </div>

        <div class="grid grid-2 share-card-layout" style="align-items:start;">
            <div class="card card-loud share-card-preview">
                <img src="{{ $cardUrl }}" alt="Your {{ $appSettings['brand_name'] }} speaker share card preview">
            </div>

            <div class="stack">
                <div class="card card-loud share-card-actions">
                    <p class="muted" style="margin:0 0 8px;">Download</p>
                    <h3 style="margin:0 0 10px;">Your speaker graphic is ready</h3>
                    <p class="muted" style="margin:0 0 16px;">Save the image first, then add it to your LinkedIn, Instagram, or other social post.</p>
                    <div class="share-card-action-row">
                        <button type="button" class="button" data-download-svg="{{ $downloadUrl }}" data-download-filename="{{ $appSettings['brand_slug'] }}-speaker-session.png">Download PNG speaker graphic</button>
                    </div>
                </div>

                <div class="card card-loud share-card-actions">
                    <p class="muted" style="margin:0 0 8px;">Caption</p>
                    <h3 style="margin:0 0 10px;">Suggested social copy</h3>
                    <textarea id="speaker-share-caption" rows="6" readonly>{{ $caption }}</textarea>
                    <div class="share-card-action-row" style="margin-top: 14px;">
                        <button type="button" class="button secondary" data-copy-target="speaker-share-caption">Copy caption</button>
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
                        button.textContent = 'Download PNG speaker graphic';
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
                } catch (error) {
                    target.focus();
                    target.select();
                    document.execCommand('copy');
                }

                button.textContent = 'Copied';
                setTimeout(() => {
                    button.textContent = 'Copy caption';
                }, 1800);
            });
        });

        document.querySelectorAll('[data-download-svg]').forEach((button) => {
            button.addEventListener('click', async () => {
                try {
                    await downloadSvgAsPng(button.getAttribute('data-download-svg'), button.getAttribute('data-download-filename') || 'speaker-card.png', button);
                } catch (error) {
                    button.textContent = 'Try again';
                    setTimeout(() => {
                        button.textContent = 'Download PNG speaker graphic';
                    }, 1800);
                }
            });
        });
    </script>
@endpush
