[![Build Status](https://travis-ci.org/danmurf/DependencyInjection.svg?branch=master)](https://travis-ci.org/danmurf/DependencyInjection) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/danmurf/DependencyInjection/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/danmurf/DependencyInjection/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/danmurf/DependencyInjection/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/danmurf/DependencyInjection/?branch=master)

# Dependency Injection Container

This is a lightweight dependency injection container, with various service location strategies, including autowiring. 

To install, run:

`composer require danmurf/dependencyinjection`

## Registered services

```php
<?php

use danmurf\DependencyInjection\ContainerFactory;

class MyClass
{
    public function myAction()
    {
        // Create a container somewhere in your kernel or bootstrap process
        $container = ContainerFactory::createRegistrationOnlyContainer();

        // Create your services and register them with the container
        $myService = new MyService();
        $container->register($myService, 'my.service.id');

        // Access your services elsewhere in your application by their registered id...
        $container->has('my.service.id'); // true
        $myService = $container->get('my.service.id');

        // ... or FQCN
        $myService = $container->get(MyService::class);
    }
}
```

## Configurable services
Have the container locate services based on services definitions.
```php
$config = [
    'my.service.id' => [
        'class' => MyService::class,
        'arguments' => [
            [
                'type' => 'scalar',
                'value' => 123,
            ],
            [
                'type' => 'service',
                'value' => 'another.service.id',
            ],
        ],
    ],
];

$container = ContainerFactory::createConfigurableContainer($config);

// The service will be located and instantiated using the defined config.
$myService = $container->get('my.service.id');
```

## Bind services to interfaces
Define which service you'd like to use for specific interfaces.
```php
$config = [
    MyInterface::class => [
        'service' => 'my.service.id'
    ],
    'my.service.id' => [
        'class' => MyService::class,
        'arguments' => [
            [
                'type' => 'scalar',
                'value' => 123,
            ],
            [
                'type' => 'service',
                'value' => 'another.service.id',
            ],
        ],
    ],
];

$container = ContainerFactory::createConfigurableContainer($config);

// The service bound to the interface will be returned
$myService = $container->get(MyInterface::class);
```

## Autowired services
Quickly create and use services by allowing the container to infer the config.
```php
// This service location strategy still requires config for services with scalar constructor arguments
$container = ContainerFactory::createAutoWireContainer([]);

// Use the FQCN to get your autowired service from the container
$myService = $container->get(MyService::class); // No config required!
```

## License
This work is provided under the MIT License. See LICENSE.md for details.
