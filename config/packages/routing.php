<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return App::config([
    'framework' => [
        'router' => [
            'resource' => 'kernel::loadRoutes',
            'default_uri' => '%env(DEFAULT_URI)%',
        ],
    ],
    'when@prod' => [
        'framework' => [
            'router' => [
                'resource' => 'kernel::loadRoutes',
                'strict_requirements' => null,
            ],
        ],
    ],
]);
