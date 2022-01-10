<?php
/**
 * Mcow Laravel modules.
 *
 * @author  Muzaffardjan Karaev
 * @link    https://karaev.uz
 * Created: 09.01.2022 / 21:06
 */
declare(strict_types=1);

namespace Mcow\LaravelModules\Generators;

use Illuminate\Container\Container;
use Mcow\LaravelModules\Module;

/**
 * Class ModuleGenerator
 * @package Mcow\LaravelModules\Generators
 */
class ModuleGenerator
{
    /** @var string */
    protected string $moduleName;

    /** @var Module */
    protected $module;

    public function __construct(Container $app, string $moduleName)
    {
        $this->moduleName = $moduleName;
        $this->module = $app['modules'];
    }

    public function generate()
    {

    }
}
