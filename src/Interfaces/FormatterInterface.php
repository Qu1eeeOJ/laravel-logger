<?php

namespace Qu1eeeOJ\LaravelLogger\Interfaces;

interface FormatterInterface
{
//    /**
//     * Set prefix to message
//     *
//     * @param string $prefix
//     *
//     * @return static
//     */
//    public function setPrefix(string $prefix): static;

    /**
     * Format the message text
     *
     * @param string $message
     *
     * @return string
     */
    public function getFormattedMessage(string $message): string;
}
