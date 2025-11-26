# Laravel Vertical Slice Architecture Rules (Pragmatic Edition)

This document outlines a pragmatic, hybrid approach to Vertical Slice Architecture in a Laravel project. The goal is to leverage feature-based organization for business logic while retaining Laravel's standard, globally accessible structure for Eloquent models.

This edition combines the purity of feature slices with the convenience of Laravel's default `app/Models` directory.

---

### 1. Core Principles

*   **Rule #1: Slice Your Logic, Centralize Your Data.**
    *   Business logic is sliced and co-located in feature folders within `app/Features`.
    *   Data structure and access (Eloquent Models) are centralized in the traditional `app/Models` directory, acting as a well-known data access layer for the entire application.

*   **Rule #2: One-Way Dependencies.**
    *   A standard feature (e.g., `CreateProduct`) can depend on code from `app/Models` and `app/Features/Common`.
    *   The `Common` feature **must not** depend on any other feature.
    *   Standard features **must not** depend on each other.

### 2. Directory Structure

This hybrid structure separates the application's "actions" (Features) from its "nouns" (Models).

```plaintext
/app
├───Features
│   ├───Common              <-- A special "feature" for shared code (non-models)
│   │   ├───Events
│   │   │   └───OrderCreated.php
│   │   └───Infrastructure
│   │       └───BaseController.php
│   │
│   ├───CreateProduct         <-- A standard "Slice" (Use Case)
│   │   │   // Depends on App/Models/Product.php
│   │   ├───CreateProductAction.php
│   │   ├───CreateProductController.php
│   │   ├───CreateProductRequest.php
│   │   ├───routes.php
│   │   └───Tests
│   │       └───CreateProductTest.php
│   │
│   └───ProcessPayment        <-- Another "Slice"
│       │   // Dispatches Common/Events/OrderCreated.php
│       └───...
│
└───Models                    <-- LARAVEL'S DEFAULT MODEL DIRECTORY
    ├───Product.php
    └───User.php
```

*   **`app/Features`**: The single entry point for all business logic, organized into slices.
*   **`app/Features/Common`**: The **Shared Kernel** for logic. It holds code that is shared across features but is not an Eloquent Model. This includes:
    *   Cross-feature Events and Listeners.
    *   Base classes (`BaseAction`, `BaseController`).
    *   Shared DTOs, Interfaces, or helper classes.
*   **`app/Features/{UseCase}`**: A standard Vertical Slice containing the logic for a specific action. It is allowed to depend on any model in `app/Models` and any code in `app/Features/Common`.
*   **`app/Models`**: The traditional Laravel directory for all Eloquent Models. These models are considered a global, shared resource for all features.

### 3. Rules for Inter-Slice Communication

*   **Rule #3: No Direct Calls Between Standard Features.**
    *   `CreateProduct` is **STRICTLY FORBIDDEN** from directly calling code in `ProcessPayment`.

*   **Rule #4: Communicate via Shared Kernel Events.**
    *   When features need to interact, they do so by dispatching events that live in `app/Features/Common`.
    *   **Example:** `ProcessPayment` dispatches `App\Features\Common\Events\PaymentProcessed`. The `SendInvoice` feature has a listener that subscribes to this event.

### 4. Configuration and Autoloading

*   **Rule #5: `composer.json` Configuration.**
    *   The `psr-4` configuration remains simple. The default `App\` namespace covers `app/Models`, and `App\Features\` covers all feature slices, including `Common`.

    ```json
    // composer.json
    "autoload": {
        "psr-4": {
            "App\\": "app/",
            "App\\Features\\": "app/Features/",
            "Database\\Factories\\": "database/factories/",
            "Database\\Seeders\\": "database/seeders/"
        }
    },
    ```
    *   After editing, always run: `composer dump-autoload`.

*   **Rule #6: Centralize Route Registration.**
    *   The `RouteServiceProvider` should be configured to scan and load all `routes.php` files from the feature directories (excluding `Common`).

    ```php
    // app/Providers/RouteServiceProvider.php
    protected function loadFeatureRoutes()
    {
        $featuresPath = app_path('Features');
        // Use RecursiveDirectoryIterator to find all route files, excluding the Common directory
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($featuresPath, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $file) {
            if (strtolower($file->getBasename()) === 'routes.php') {
                // Exclude routes from the Common feature
                if (strpos($file->getPathname(), app_path('Features/Common')) === false) {
                    Route::middleware('web') // or 'api'
                         ->group($file->getRealPath());
                }
            }
        }
    }
    ```

### 5. Testing

*   **Rule #7: Co-locate Tests with Features.**
    *   Test files for a slice **must** be placed inside the feature's own `Tests` folder.
    *   Example: `app/Features/CreateProduct/Tests/CreateProductTest.php`.

---

This pragmatic approach provides a healthy balance: it organizes volatile business logic into clean, maintainable slices while keeping the stable data layer in a familiar, framework-standard location.
