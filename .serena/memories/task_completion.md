# Task Completion Checklist
- Ensure changes follow vertical slice structure and existing naming patterns; keep models in `app/Models`.
- Run `vendor/bin/pint --dirty` to match code style.
- Run the minimal relevant PHPUnit tests (e.g., specific feature file or filter); consider `php artisan test` when unsure. Offer to run full suite if only targeted tests executed.
- Avoid altering dependencies without approval; stick to existing directories.
- Use Laravel generators (`php artisan make:* --no-interaction`) for new classes when possible.
- If frontend visible changes are made, remind user to run `npm run dev` or `npm run build`.