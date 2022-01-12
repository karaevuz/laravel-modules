<?php
/**
 * Mcow Laravel modules.
 *
 * @author  Muzaffardjan Karaev
 * @link    https://karaev.uz
 * Created: 09.01.2022 / 00:49
 */
declare(strict_types=1);

return [
    'namespace' => 'LaravelModules',

    'paths' => [
        'modules'   => base_path('modules'),
        'migration' => base_path('database/migrations'),

        'folders' => [
            'config'     => 'config',
            'command'    => 'Console',
            'factories'  => 'database/factories',
            'migration'  => 'database/migrations',
            'seeder'     => 'database/seeder',
            'model'      => 'Models',
            'controller' => 'Http/Controllers',
            'middleware' => 'Http/Middleware',
            'provider'   => 'Providers',
            'lang'       => 'Resources/lang',
            'views'      => 'Resources/views',
            'routes'     => 'Routes',
        ],
    ],

    'tpl' => [
        'path' => base_path() . '/vendor/mcow/laravel-modules/src/Commands/tpl',

        'files' => [
            'config/config' => 'config/config.php',
            'views/index'   => 'Resources/views/index.blade.php',
            'views/master'  => 'Resources/views/layouts/main.blade.php',
            'routes/web'    => 'Routes/web.php',
            'routes/api'    => 'Routes/api.php',
        ],

        'replaces' => [
            'views/index'   => [],
            'views/master'  => [],
            'routes/web'    => [],
            'routes/api'    => [],
        ],
    ],

    'cache' => [
        'enabled'  => false,
        'key'      => 'mcow-modules',
        'lifetime' => 60,
    ],
];
