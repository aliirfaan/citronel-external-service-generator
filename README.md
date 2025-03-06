# Citronel external service generator

Generate configuration and classes to consume an external service that use the package `aliirfaan/citronel-external-service`.

## Features
* Generate a configuration file based on a format.
* Generate service class to consume external service.
* Custom stub to generate service class, models, events and listeners.
* Helper function for caching responses.

## Requirements

* [Composer](https://getcomposer.org/)
* [Laravel](http://laravel.com/)
* aliirfaan/citronel-external-service

## Installation

* Install the package using composer as a dev requirement:

```bash
 $ composer require aliirfaan/citronel-external-service-generator --dev
```

## Usage
An example of how to consume an example external service [httpbin](https://httpbin.org/).

### Generate configuration

To generate configuration file we call the command `citronel:external-service-generate:config {external_service_name}`. Replace {external_service_name} with a sensible name for your external service.

```bash
 $ php artisan citronel:external-service-generate:config http-bin-platform
```



```php
<?php

return [
    'web_service' => [
        'base_url' => env('HTTP_BIN_PLATFORM_BASE_URL'),
        'connect_timeout_seconds' => env('HTTP_BIN_PLATFORM_CONNECT_TIMEOUT_SECONDS', 10),
        'timeout_seconds' => env('HTTP_BIN_PLATFORM_TIMEOUT_SECONDS', 60),
        'username' => env('HTTP_BIN_PLATFORM_USERNAME'),
        'password' => env('HTTP_BIN_PLATFORM_PASSWORD'),
        'api_key' => env('HTTP_BIN_PLATFORM_KEY'),
        'endpoints' => [
            'ip_endpoint' => [
                'api_operation' => 'ip',
                'endpoint' => '/example-endpoint',
                'method' => 'GET',
                'logging' => [ // this will override the global logging settings and can be omitted if not needed
                    'should_log' => env('HTTP_BIN_PLATFORM_SHOULD_LOG'),
                    'requests' => [
                        'should_log' => env('HTTP_BIN_PLATFORM_SHOULD_LOG_REQUESTS'),
                    ],
                    'responses' => [
                        'should_log' => env('HTTP_BIN_PLATFORM_SHOULD_LOG_RESPONSES'),
                        'log_channel' => env('HTTP_BIN_PLATFORM_LOG_RESPONSE_CHANNEL'),
                    ]
                ],
                'caching' => [
                    'should_cache' => env('HTTP_BIN_PLATFORM_SHOULD_CACHE'),
                    'cache_key' => 'HTTP_BIN_PLATFORM_example',
                    'cache_seconds' => env('HTTP_BIN_PLATFORM_CACHE_EXAMPLE_ENDPOINT_SEC', 3600),
                ]
            ]
        ],
    ],
    'logging' => [
        'should_log' => env('HTTP_BIN_PLATFORM_SHOULD_LOG', false),
        'requests' => [
            'should_log' => env('HTTP_BIN_PLATFORM_SHOULD_LOG_REQUESTS', true),
            'event_class' => env('HTTP_BIN_PLATFORM_LOG_REQUEST_EVENT_CLASS', App\Events\Test::class),
            'model' => env('HTTP_BIN_PLATFORM_LOG_REQUEST_MODEL', App\Models\Request::class),
        ],
        'responses' => [
            'should_log' => env('HTTP_BIN_PLATFORM_SHOULD_LOG_RESPONSES', true),
            'event_class' => env('HTTP_BIN_PLATFORM_LOG_RESPONSE_EVENT_CLASS', App\Events\Test::class),
            'model' => env('HTTP_BIN_PLATFORM_LOG_RESPONSE_MODEL', App\Models\Response::class),
            'log_response_channel' => env('HTTP_BIN_PLATFORM_LOG_RESPONSE_CHANNEL', 'HTTP_BIN_PLATFORM_response', null),
        ],
    ],
    'caching' => [
        'should_cache' => env('HTTP_BIN_PLATFORM_SHOULD_CACHE', false),
    ],
    'pruning' => [
        'should_prune' => env('HTTP_BIN_PLATFORM_SHOULD_PRUNE', true),
        'requests' => [
            'should_prune' => env('HTTP_BIN_PLATFORM_SHOULD_PRUNE_REQUESTS', true),
            'prune_days' => env('HTTP_BIN_PLATFORM_PRUNE_REQUESTS_DAYS', 60),
        ],
        'responses' => [
            'should_prune' => env('HTTP_BIN_PLATFORM_SHOULD_PRUNE_RESPONSES', true),
            'prune_days' => env('HTTP_BIN_PLATFORM_PRUNE_RESPONSES_DAYS', 60),
        ]
    ]
];
```

## Migration and models

### Generating external service request migration and model

* Generate migration using custom stub. **Name your model accordingly before running the command**.

```bash
 $ php artisan citronel:external-service-generate:log --log_type=request HttpBinPlatform/HttpBinPlatformReq http-bin
```
* Add your custom columns to the newly generated migration.

* Update your config file with the newly model class.

### Generating external service response migration and model

* Generate migration using custom stub. **Name your model accordingly before running the command**.

```bash
 $ php artisan citronel:external-service-generate:log --log_type=response HttpBinPlatform/HttpBinPlatformResp http-bin
```
* Add your custom columns to the newly generated migration.

* Update your config file with the newly model class.

### Run your newly generated migration to create tables
You can run migration later if you do not know what fields to log.
```bash
 $ php artisan migrate
```

## Request/response events

### Generating external service request event

* Generate request event using custom stub. **Name your event accordingly before running the command**.

```bash
 $ php artisan citronel:external-service-generate:event HttpBinPlatform/HttpBinPlatformRequestSent
```
* Update your config file with the newly created event class.

### Generating external service response event

* Generate response event using custom stub. **Name your event accordingly before running the command**.

```bash
 $ php artisan citronel:external-service-generate:event HttpBinPlatform/HttpBinPlatformResponseReceived
```
* Update your config file with the newly created event class.

## Listener

### Generating event subscriber

* Generate subscriber using custom stub. **Name your listener accordingly before running the command**.

```bash
 $ php artisan citronel:external-service-generate:listener HttpBinPlatform/HttpBinPlatformEventSubscriber http-bin
```
## Service

### Generating service clas

* Generate service using custom stub. **Name your service accordingly before running the command**.

```bash
 $ php artisan citronel:external-service-generate:service HttpBinPlatform/HttpBinPlatformService http-bin
```

## aliirfaan/citronel-external-service
* Install the package aliirfaan/citronel-external-service-generator if you have not already done so:

```bash
 $ composer require aliirfaan/citronel-external-service
```
* See documentation of `aliirfaan/citronel-external-service` package on how to use newly generated classes.