# Setup Notes

Last updated: 2026-03-26

## Current Status

This repository was cloned from:

- `https://github.com/devaslanphp/project-management.git`

The project has been upgraded and adjusted to run on a modern stack:

- Laravel `12.55.1`
- Filament `3.3.49`
- Livewire `3.7.12`
- PHP target updated in Composer to `^8.2`
- Frontend toolchain upgraded to modern Vite / Tailwind-compatible versions

## What Was Done

- Upgraded PHP dependencies in `composer.json` / `composer.lock`
- Migrated Filament v2-era code to Filament v3-compatible APIs
- Fixed multiple method visibility / signature issues across pages, widgets, resources, and relation managers
- Updated Filament panel bootstrapping to use a panel provider
- Fixed frontend build configuration and moved Vite config to `vite.config.mjs`
- Updated Filament theme import path in `resources/css/filament.scss`
- Created local `.env`
- Generated `APP_KEY`
- Ran `php artisan storage:link`
- Verified:
  - `php artisan package:discover`
  - `php artisan route:list`
  - `php artisan about`
  - `npm run build`

## Important Local Files

- Local Composer binary: `./composer`
- Vite config: `./vite.config.mjs`
- Setup/runtime notes: `./SETUP-NOTES.md`

## Remaining External Requirement

The application still needs a database service before final first-run validation can be completed.

Required:

- MySQL `8.x`, or
- Docker Desktop so `docker compose up --build` can run

At the time of this note:

- `docker` was not installed on this machine
- `mysql` client/server was not installed on this machine

Because of that, the following has **not** been completed yet:

- `php artisan migrate --seed`
- Browser/login smoke test against a real running app with a live database

## Recommended Next Step

After installing MySQL or Docker:

1. Ensure `.env` has valid database credentials.
2. Run `php artisan migrate --seed`
3. Run `php artisan serve`
4. Open the app and verify login / dashboard

## Default Seed Login

If seeding runs successfully, the default seeded user is:

- Email: `john.doe@helper.app`
- Password: `Passw@rd`

## Notes

- `.env.example` was adjusted to be safer for first-time local setup:
  - `BROADCAST_DRIVER=log`
  - `QUEUE_CONNECTION=sync`
  - `MAIL_MAILER=log`
- Docker files were modernized to use the current project source instead of the old published image.
- There is a local Composer binary because Composer was not available globally.
