<?php

namespace aliirfaan\CitronelExternalServiceGenerator\Services;

use aliirfaan\CitronelExternalServiceGenerator\Services\GeneratorService;

class ExternalServiceGeneratorService
{
    public $generatorService;

    public function __construct(GeneratorService $generatorService)
    {
        $this->generatorService = $generatorService;
    }

    public function generate(string $servicePath, string $configKey)
    {
        $data = [
            'success' => false,
            'result' => null,
            'errors' => null,
            'message' => null,
            'issues' => [],
        ];

        $stubFile = __DIR__ . '/../../stubs/app/Services/external-service.create.stub';


        // get model name from path
        $serviceName = $this->generatorService->generateClassNameFromPath($servicePath);
        $stub = file_get_contents($stubFile);
        $stub = str_replace('DummyClass', $serviceName, $stub);
        $stub = str_replace('{{ config-key }}', $configKey, $stub);

        $serviceNamespace = $this->generatorService->generateNamespaceFromPath($servicePath, 'service');
        $stub = str_replace('DummyNamespace', $serviceNamespace, $stub);
        $servicePathWithExtension = $servicePath . '.php';
        $servicePath = $this->generatorService->generateFilePath($servicePathWithExtension, 'service');
        if (file_exists($servicePath)) {
            $data['errors'] = true;
            $data['message'] = 'File creation skipped: file already exists.';
        }

        if (is_null($data['errors'])) {
            // Ensure the directory exists
            $directory = dirname($servicePath);
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
            file_put_contents($servicePath, $stub);

            $data['success'] = true;
            $data['message'] = 'File created successfully.';
        }

        return $data;
    }
}
