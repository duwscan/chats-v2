# Suggested Commands
- Install & setup: `composer run setup` (installs deps, copies .env, key:generate, migrate, npm install, build).
- Dev server stack: `composer run dev` (concurrently runs serve, queue:listen, pail logs, npm dev).
- Run targeted tests: `php artisan test tests/Feature/...` or `php artisan test --filter=TestName`; full suite via `composer test` or `php artisan test`.
- Lint/format: `vendor/bin/pint --dirty` before committing changes.
- Artisan generators: `php artisan make:class|controller|request ... --no-interaction` for new slice files; `php artisan tinker` for quick debugging when appropriate.
- Asset build (if UI changes): `npm run build` or `npm run dev` (Tailwind v4).