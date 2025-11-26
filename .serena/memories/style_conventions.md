# Style & Conventions
- Follow Vertical Slice layout: feature folders under `app/Features/{Slice}` with Controller, Action, FormRequest, Resources, `routes.php`; shared logic would live in `Features/Common` (not present yet). Models remain in `app/Models`.
- Use Laravel form requests for validation; controllers are single-action invokable classes using `ApiResponseTrait` to wrap responses.
- PHP: explicit return types, control structures with braces, prefer constructor property promotion; avoid inline comments, use PHPDoc when needed (especially for arrays/shapes).
- Naming: descriptive method/variable names; enums TitleCase if added.
- Routing: feature `routes.php` files use `Route::middleware('api')` and `->prefix('api')` where needed, grouping per slice.
- Eloquent: prefer model queries over raw DB; eager load to avoid N+1; configure casts via `casts()` when relevant.
- Tailwind v4 CSS-first import if touching frontend; keep dark-mode parity if existing.
- Tests: PHPUnit (no Pest); place new tests under `tests` or co-locate per slice if following vertical slice guidance; use factories for models.