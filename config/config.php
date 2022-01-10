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
    ],

    'cache' => [
        'enabled'  => false,
        'key'      => 'mcow-modules',
        'lifetime' => 60,
    ],
];
