<?php
/**
 * Mcow Laravel modules.
 *
 * @author  Muzaffardjan Karaev
 * @link    https://karaev.uz
 * Created: 12.01.2022 / 21:46
 */
declare(strict_types=1);

namespace Mcow\LaravelModules;

use Illuminate\Container\Container;
use Illuminate\Support\Arr;
use Mcow\LaravelModules\Extensions\McowJson;
use Illuminate\Filesystem\Filesystem;

/**
 * Class Module
 * @package Mcow\LaravelModules
 */
class Module
{
    /** @var \Illuminate\Contracts\Foundation\Application */
    protected $app;

    /** @var string */
    protected string $name;

    /** @var string */
    protected string $path;

    /** @var array */
    protected array $moduleJson = [];

    /** @var Filesystem */
    protected $fileSystem;

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

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getLowerName(): string
    {
        return strtolower($this->name);
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return Filesystem
     */
    public function getFileSystem(): Filesystem
    {
        return $this->fileSystem;
    }

    /**
     * @param string|null $file
     * @return McowJson
     */
    public function fromJson(string $file = null): McowJson
    {
        $file = is_null($file) ? 'module.json' : $file;

        return Arr::get($this->moduleJson, $file, function () use ($file) {
            return $this->moduleJson[$file] = new McowJson($this->getPath() . '/' . $file);
        });
    }

    /**
     * @return bool
     */
    public function delete(): bool
    {
        return $this->fromJson()->getFileSystem()->deleteDirectory($this->getPath());
    }
}
