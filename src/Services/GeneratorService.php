<?php

namespace aliirfaan\CitronelExternalServiceGenerator\Services;

use Illuminate\Support\Str;

class GeneratorService
{
    /**
     * Generates a configuration file name based on the external service name.
     *
     * @param string $externalServiceName
     *
     * @return string
     */
    public function generateConfigurationFileName(string $externalServiceName): string
    {
        $sanitizedServiceName = str_replace([' '], '-', $externalServiceName);

        return strtolower($sanitizedServiceName) . '.php';
    }
    
    /**
     * main process name that can be used for logging purposes
     *
     * @param string $externalServiceName [explicite description]
     *
     * @return string
     */
    public function generateMainProcessName(string $externalServiceName): string
    {
        $sanitizedServiceName = str_replace(['-', ' '], '_', $externalServiceName);

        return strtolower($sanitizedServiceName);
    }
    
    /**
     * Generate a namespace from a given path and type following Laravel's standard.
     *
     * @param string $path
     * @param string $type
     * @return string
     */
    public function generateNamespaceFromPath(string $path, string $type): string
    {
        // Remove leading and trailing slashes
        $trimmedPath = trim($path, '/');

        // Remove the last segment (class name)
        $pathSegments = explode('/', $trimmedPath);
        array_pop($pathSegments);
        $trimmedPath = implode('/', $pathSegments);

        // Replace slashes with backslashes
        $namespace = str_replace('/', '\\', $trimmedPath);

        // Define the base namespace based on the type
        switch ($type) {
            case 'model':
                $baseNamespace = 'App\\Models';
                break;
            case 'controller':
                $baseNamespace = 'App\\Http\\Controllers';
                break;
            case 'service':
                $baseNamespace = 'App\\Services';
                break;
            case 'event':
                $baseNamespace = 'App\\Events';
                break;
            case 'listener':
                $baseNamespace = 'App\\Listeners';
                break;
            default:
                $baseNamespace = 'App';
                break;
        }

        // Ensure the namespace starts with the base namespace
        if (strpos($namespace, $baseNamespace) !== 0) {
            $namespace = $baseNamespace . '\\' . $namespace;
        }

        return $namespace;
    }
    
    /**
     * Generate a class name based on the path
     *
     * @param string $path
     *
     * @return string
     */
    public function generateClassNameFromPath(string $path): string
    {
        // Remove leading and trailing slashes
        $trimmedPath = trim($path, '/');

        // Remove the last segment (class name)
        $pathSegments = explode('/', $trimmedPath);
        
        return array_pop($pathSegments);
    }

    /**
     * Generate a migration file name based on a studly model name
     *
     * @param string $modelName
     * @param string $action
     * @return string
     */
    public function generateMigrationFileNameFromModel(string $modelName, string $action = 'create'): string
    {
        // Convert the model name to snake case
        $tableName = Str::snake(Str::plural($modelName));

        // Generate a timestamp
        $timestamp = date('Y_m_d_His');

        // Format the migration file name
        return $timestamp . '_' . $action . '_' . $tableName . '_table.php';
    }

    /**
     * Generate a file path from a given path
     *
     * @param string $path
     * @param string $type
     * @return string
     */
    public function generateFilePath(string $path, string $type): string
    {
        // Define the base directory based on the type
        switch ($type) {
            case 'model':
                $baseDir = app_path('Models');
                break;
            case 'controller':
                $baseDir = app_path('Http/Controllers');
                break;
            case 'migration':
                $baseDir = database_path('migrations');
                break;
            case 'seeder':
                $baseDir = database_path('seeders');
                break;
            case 'factory':
                $baseDir = database_path('factories');
                break;
            case 'service':
                $baseDir = app_path('Services');
                break;
            case 'event':
                $baseDir = app_path('Events');
                break;
            case 'listener':
                $baseDir = app_path('Listeners');
                break;
            case 'config':
                $baseDir = config_path();
                break;
            default:
                throw new \InvalidArgumentException("Invalid type: $type");
        }

        // Ensure the directory exists
        if (!is_dir($baseDir)) {
            mkdir($baseDir, 0755, true);
        }

        // Generate the file path
        return $baseDir . '/' . $path;
    }

    /**
     * Generate a table name from a model name following Laravel's standard.
     *
     * @param string $modelName
     * @return string
     */
    public function generateTableNameFromModel(string $modelName): string
    {
        // Convert the model name to snake case and pluralize it
        return Str::snake(Str::plural($modelName));
    }
}
