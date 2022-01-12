<?php
/**
 * Mcow Laravel modules.
 *
 * @author  Muzaffardjan Karaev
 * @link    https://karaev.uz
 * Created: 12.01.2022 / 12:27
 */
declare(strict_types=1);

namespace Mcow\LaravelModules\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Module
 * @package Mcow\LaravelModules\Facades
 */
class Module extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'modules';
    }
}
