<?php
/**
 * Mcow Laravel modules.
 *
 * @author  Muzaffardjan Karaev
 * @link    https://karaev.uz
 * Created: 09.01.2022 / 08:05
 */
declare(strict_types=1);

namespace Mcow\LaravelModules;

use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Traits\Macroable;

/**
 * Class Module
 * @package Mcow\LaravelModules
 */
class Module
{
    use Macroable;

    /** @var \Illuminate\Contracts\Foundation\Application */
    protected $app;

    /** @var string */
    protected string $name;

    /** @var string */
    protected string $path;

    /** @var Filesystem */
    private $fileSystem;

    /**
     * Module constructor.
     * @param Container $app
     * @param string    $name
     * @param string    $path
     */
    public function __construct(Container $app, string $name, string $path)
    {
        $this->app = $app;
        $this->name = $name;
        $this->path = $path;

        $this->fileSystem = $app['files'];
    }
}
