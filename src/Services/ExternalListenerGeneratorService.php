<?php

namespace aliirfaan\CitronelExternalServiceGenerator\Services;

use aliirfaan\CitronelExternalServiceGenerator\Services\GeneratorService;

/**
 * Generates migration and model for request and response logs of external services.
 */
class ExternalListenerGeneratorService
{
    public $generatorService;

    public function __construct(GeneratorService $generatorService)
    {
        $this->generatorService = $generatorService;
    }

    public function generate(string $listenerPath, string $configKey)
    {
        $data = [
            'success' => false,
            'result' => null,
            'errors' => null,
            'message' => null,
            'issues' => [],
        ];

        $stubFile = __DIR__ . '/../../stubs/app/Listeners/external-service.subscriber.create.stub';


        // get model name from path
        $listenerName = $this->generatorService->generateClassNameFromPath($listenerPath);
        $stub = file_get_contents($stubFile);
        $stub = str_replace('DummyClass', $listenerName, $stub);
        $stub = str_replace('{{ config-key }}', $configKey, $stub);

        $listenerNamespace = $this->generatorService->generateNamespaceFromPath($listenerPath, 'listener');
        $stub = str_replace('DummyNamespace', $listenerNamespace, $stub);
        $listenerPathWithExtension = $listenerPath . '.php';
        $listenerPath = $this->generatorService->generateFilePath($listenerPathWithExtension, 'listener');
        if (file_exists($listenerPath)) {
            $data['errors'] = true;
            $data['message'] = 'File creation skipped: file already exists.';
        }

        if (is_null($data['errors'])) {
            // Ensure the directory exists
            $directory = dirname($listenerPath);
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
            file_put_contents($listenerPath, $stub);

            $data['success'] = true;
            $data['message'] = 'File created successfully.';
        }

        return $data;
    }
}
