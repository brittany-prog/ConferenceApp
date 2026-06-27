# ConferenceApp

White-label Laravel conference app derived from the Southern Spark build, prepared for deployment as a separate branded instance.

## Cloudways First Deploy

Before you hit Deploy via Git in Cloudways, make sure the new app has:

1. PHP `8.2+`
2. A database created and wired into the app `.env`
3. A generated `APP_KEY`
4. `APP_URL` set to the Cloudways app URL or mapped domain

Recommended `.env` values for first boot:

```env
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=mysql
CACHE_STORE=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=public
```

## Post-Deploy Commands

Run these on the Cloudways app after the first Git deploy:

```sh
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev
php artisan key:generate --force
php artisan storage:link || true
php artisan migrate --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Or run the bundled helper:

```sh
sh scripts/cloudways-deploy.sh
```

## White-Label Settings

The cloned app supports env-driven or admin-managed branding for:

- Brand name, tagline, colors, and logo
- Public ticket CTA
- Event date and city label
- Login screen copy
- Footer branding
- External community CTA

## Important Notes

- This repo still includes Southern Spark starter data and generated assets. The app is deployable, but you may want a second cleanup pass before public launch.
- Do not commit your real `.env` file.
- Rotate any GitHub or infrastructure secrets that were pasted into chat.
