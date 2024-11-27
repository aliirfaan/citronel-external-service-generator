<?php

namespace aliirfaan\CitronelExternalServiceGenerator\Services;

use aliirfaan\CitronelExternalServiceGenerator\Services\GeneratorService;

class ConfigurationGeneratorService
{
    public $generatorService;

    public function __construct(GeneratorService $generatorService)
    {
        $this->generatorService = $generatorService;
    }

    /**
     *
     * @param string $externalServiceName
     *
     * @return array
     */
    public function generate(string $externalServiceName)
    {
        $data = [
            'success' => false,
            'result' => null,
            'errors' => null,
            'message' => null,
            'issues' => [],
        ];

        $stubFile = __DIR__ . '/../../stubs/config/config.create.stub';
        $stub = file_get_contents($stubFile);

        $sanitizedServiceName = str_replace(['-', ' '], '_', $externalServiceName);
        $upperCaseServiceName = strtoupper($sanitizedServiceName);
        $stub = str_replace('{{ external_service_name }}', $upperCaseServiceName, $stub);

        $fileName = $this->generatorService->generateConfigurationFileName($externalServiceName);

        $filePath = $this->generatorService->generateFilePath($fileName, 'config');

        // Check if the configuration file already exists
        if (file_exists($filePath)) {
            $data['errors'] = true;
            $data['message'] = 'File creation skipped: file already exists.';
        }

        if (is_null($data['errors'])) {
            file_put_contents($filePath, $stub);
            
            $data['success'] = true;
            $data['message'] = 'File created successfully.';
        }

        return $data;
    }
}
