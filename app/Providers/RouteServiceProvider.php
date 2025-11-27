<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Get all feature route files.
     *
     * @return array<string>
     */
    public static function getFeatureRouteFiles(): array
    {
        $featuresPath = app_path('Features');
        $routeFiles = [];

        if (! is_dir($featuresPath)) {
            return $routeFiles;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($featuresPath, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && strtolower($file->getBasename()) === 'routes.php') {
                $filePath = $file->getRealPath();
                // Exclude routes from the Common feature
                if (strpos($filePath, app_path('Features/Common')) === false) {
                    $routeFiles[] = $filePath;
                }
            }
        }

        return $routeFiles;
    }

    /**
     * Load all feature routes.
     * This method is called from routes/api.php to load routes.
     */
    public static function loadFeatureRoutes(): void
    {
        foreach (self::getFeatureRouteFiles() as $routeFile) {
            require $routeFile;
        }
    }
}
