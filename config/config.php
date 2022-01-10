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
        'modules' => base_path('modules'),

        'generator' => [
            'config'          => 'config',
            'command'         => 'Console',
            'migration'       => ['path' => 'Database/Migrations',        'generate' => true],
            'seeder'          => ['path' => 'Database/Seeders',           'generate' => true],
            'factory'         => ['path' => 'Database/factories',         'generate' => true],
            'model'           => ['path' => 'Entities',                   'generate' => true],
            'routes'          => ['path' => 'Routes',                     'generate' => true],
            'controller'      => ['path' => 'Http/Controllers',           'generate' => true],
            'filter'          => ['path' => 'Http/Middleware',            'generate' => true],
            'request'         => ['path' => 'Http/Requests',              'generate' => true],
            'provider'        => ['path' => 'Providers',                  'generate' => true],
            'assets'          => ['path' => 'Resources/assets',           'generate' => true],
            'lang'            => ['path' => 'Resources/lang',             'generate' => true],
            'views'           => ['path' => 'Resources/views',            'generate' => true],
            'test'            => ['path' => 'Tests/Unit',                 'generate' => true],
            'test-feature'    => ['path' => 'Tests/Feature',              'generate' => true],
            'repository'      => ['path' => 'Repositories',               'generate' => false],
            'event'           => ['path' => 'Events',                     'generate' => false],
            'listener'        => ['path' => 'Listeners',                  'generate' => false],
            'policies'        => ['path' => 'Policies',                   'generate' => false],
            'rules'           => ['path' => 'Rules',                      'generate' => false],
            'jobs'            => ['path' => 'Jobs',                       'generate' => false],
            'emails'          => ['path' => 'Emails',                     'generate' => false],
            'notifications'   => ['path' => 'Notifications',              'generate' => false],
            'resource'        => ['path' => 'Transformers',               'generate' => false],
            'component-view'  => ['path' => 'Resources/views/components', 'generate' => false],
            'component-class' => ['path' => 'View/Component',             'generate' => false],
        ],
    ],

    'cache' => [
        'enabled'  => false,
        'key'      => 'mcow-modules',
        'lifetime' => 60,
    ],
];
