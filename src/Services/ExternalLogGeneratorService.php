<?php

namespace aliirfaan\CitronelExternalServiceGenerator\Services;

use aliirfaan\CitronelExternalServiceGenerator\Services\GeneratorService;

/**
 * Generates migration and model for request and response logs of external services.
 */
class ExternalLogGeneratorService
{
    public $generatorService;

    public function __construct(GeneratorService $generatorService)
    {
        $this->generatorService = $generatorService;
    }

    public function generate(string $logType, string $modelPath, string $configKey)
    {
        $data = [
            'success' => false,
            'result' => null,
            'errors' => null,
            'message' => null,
            'issues' => [],
        ];

        $migrationStubFile = __DIR__ . '/../../stubs/database/migrations/external_service_request.migration.create.stub';

        $modelStubFile = __DIR__ . '/../../stubs/app/Models/external-service_request.model.create.stub';

        switch ($logType) {
            case 'request':
                $migrationStubFile = __DIR__ . '/../../stubs/database/migrations/external_service_request.migration.create.stub';
                $modelStubFile = __DIR__ . '/../../stubs/app/Models/external-service_request.model.create.stub';
                break;
            case 'response':
                $migrationStubFile = __DIR__ . '/../../stubs/database/migrations/external_service_response.migration.create.stub';
                $modelStubFile = __DIR__ . '/../../stubs/app/Models/external-service_response.model.create.stub';
                break;
            default:
                $data['errors'] = true;
                $data['message'] = 'Invalid log type.';
                break;
        }

        // get model name from path
        $modelName = $this->generatorService->generateClassNameFromPath($modelPath);
        $modelStub = file_get_contents($modelStubFile);
        $modelStub = str_replace('DummyClass', $modelName, $modelStub);
        $modelStub = str_replace('{{ config-key }}', $configKey, $modelStub);

        $modelNamespace = $this->generatorService->generateNamespaceFromPath($modelPath, 'model');
        $modelStub = str_replace('DummyNamespace', $modelNamespace, $modelStub);
        $modelPathWithExtension = $modelPath . '.php';
        $modelFilePath = $this->generatorService->generateFilePath($modelPathWithExtension, 'model');
        if (file_exists($modelFilePath)) {
            $data['errors'] = true;
            $data['message'] = 'File creation skipped: file already exists.';
        }

        if (is_null($data['errors'])) {
            // Ensure the directory exists
            $directory = dirname($modelFilePath);
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
            file_put_contents($modelFilePath, $modelStub);

            $tableName = $this->generatorService->generateTableNameFromModel($modelName);
            $migrationStub = file_get_contents($migrationStubFile);
            $migrationStub = str_replace('{{ table }}', $tableName, $migrationStub);
            $migrationStub = str_replace('{{ table }}', $tableName, $migrationStub);

            $migrationFileName = $this->generatorService->generateMigrationFileNameFromModel($modelName);
            $migrationFilePath = $this->generatorService->generateFilePath($migrationFileName, 'migration');

            file_put_contents($migrationFilePath, $migrationStub);

            $data['success'] = true;
            $data['message'] = 'File created successfully.';
        }

        return $data;
    }
}
