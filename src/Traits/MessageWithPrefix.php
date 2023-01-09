<?php

namespace Qu1eeeOJ\LaravelLogger\Traits;

trait MessageWithPrefix
{
    /**
     * @var string
     */
    public static string $separator = ' -> ';

    /**
     * @var array<int, string>
     */
    private array $prefixes = [];

    /**
     * Get prefix
     *
     * @return string
     */
    public function getPrefix(): string
    {
        return implode(self::$separator, $this->prefixes);
    }

    /**
     * Add text to the beginning of the prefix
     *
     * @param string $text
     *
     * @return void
     */
    public function addTextToBeginningPrefix(string $text): void
    {
        array_unshift($this->prefixes, $text);
    }

    /**
     * Add text to the end of the prefix
     *
     * @param string $text
     *
     * @return void
     */
    public function addTextToEndPrefix(string $text): void
    {
        $this->prefixes[] = $text;
    }

    /**
     * Set prefix
     *
     * @param string|array $prefix
     *
     * @return void
     */
    public function setPrefix(string|array $prefix): void
    {
        $this->prefixes = is_array($prefix) ? $prefix : [$prefix];
    }
}
