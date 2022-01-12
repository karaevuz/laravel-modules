<?php
/**
 * Mcow Laravel modules.
 *
 * @author  Muzaffardjan Karaev
 * @link    https://karaev.uz
 * Created: 12.01.2022 / 12:41
 */
declare(strict_types=1);

namespace Mcow\LaravelModules\Extensions;

/**
 * Class McowTemplate
 * @package Mcow\LaravelModules\Extensions
 */
class McowTemplate
{
    /** @var string|null */
    protected static ?string $basePath = null;

    /** @var string */
    protected string $path;

    /** @var array */
    protected array $replaces = [];

    /**
     * McowTemplate constructor.
     * @param string $path
     * @param array  $replaces
     */
    public function __construct(string $path, array $replaces = [])
    {
        $this->path = $path;
        $this->replaces = $replaces;
    }

    /**
     * @param string $path
     * @return void
     */
    public static function setBasePath(string $path)
    {
        static::$basePath = $path;
    }

    /**
     * @return string|null
     */
    public static function getBasePath(): ?string
    {
        return static::$basePath;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        $path = static::getBasePath() . $this->path;

        return file_exists($path) ? $path : __DIR__ . '/../Commands/template' . $this->path;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $contents = file_get_contents($this->getPath());

        foreach ($this->replaces as $search => $replace) {
            $contents = str_replace('$' . strtoupper($search) . '$', $replace, $contents);
        }

        return $contents;
    }

    /**
     * @param string $path
     * @param array  $replaces
     * @return McowTemplate
     */
    public static function make(string $path, array $replaces = []): McowTemplate
    {
        return new static($path, $replaces);
    }

    /**
     * Handle magic method __toString.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}
