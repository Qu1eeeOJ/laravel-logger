<?php

namespace Qu1eeeOJ\LaravelLogger\Interfaces;

interface FormatterWithStyleInterface extends FormatterInterface
{
    /**
     * Set style
     *
     * @param string $style
     *
     * @return static
     */
    public function setStyle(string $style): static;
}
