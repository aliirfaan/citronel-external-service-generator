<?php

namespace aliirfaan\CitronelExternalServiceGenerator\Services;

use aliirfaan\CitronelExternalServiceGenerator\Services\GeneratorService;

/**
 * Generates events for request and response logs of external services.
 */
class ExternalEventGeneratorService
{
    public $generatorService;

    public function __construct(GeneratorService $generatorService)
    {
        $this->generatorService = $generatorService;
    }

    public function generate(string $eventPath)
    {
        $data = [
            'success' => false,
            'result' => null,
            'errors' => null,
            'message' => null,
            'issues' => [],
        ];

        $stubFile = __DIR__ . '/../../stubs/app/Events/external-service.event.create.stub';

        // get model name from path
        $eventName = $this->generatorService->generateClassNameFromPath($eventPath);
        $stub = file_get_contents($stubFile);
        $stub = str_replace('DummyClass', $eventName, $stub);

        $eventNamespace = $this->generatorService->generateNamespaceFromPath($eventPath, 'event');
        $stub = str_replace('DummyNamespace', $eventNamespace, $stub);
        $eventlPathWithExtension = $eventPath . '.php';
        $eventFilePath = $this->generatorService->generateFilePath($eventlPathWithExtension, 'event');
        if (file_exists($eventFilePath)) {
            $data['errors'] = true;
            $data['message'] = 'File creation skipped: file already exists.';
        }

        if (is_null($data['errors'])) {
            // Ensure the directory exists
            $directory = dirname($eventFilePath);
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
            file_put_contents($eventFilePath, $stub);

            $data['success'] = true;
            $data['message'] = 'File created successfully.';
        }

        return $data;
    }
}
