# Project Overview
- Laravel 12 API service for managing chat channels (currently Facebook and LINE) plus custom app webhook configs; exposes endpoints to create/update channel configs and process webhooks.
- Vertical Slice architecture under `app/Features/*` (see `laravel-vertical-slice.md`): each feature bundles controller/action/request/resource/routes; shared models stay in `app/Models`.
- API responses standardized via `App\Http\Controllers\ApiResponseTrait` with `_status`, `_success`, `_messages`, `_data`, `_extra` blocks; exceptions are formatted in `bootstrap/app.php`.
- Routes are centralized in `routes/api.php`, which requires each feature's `routes.php`. Web and console routes are minimal defaults.
- Database uses sqlite by default; key tables include `channel_webhook_configs` for channel configs and `app_channel_webhook_configs` for custom app channel domains.
- Key packages/tools: PHP 8.4, Laravel 12, PHPUnit 11, Pint 1, Tailwind 4; dev helpers include Laravel Boost, Pail, Sail.