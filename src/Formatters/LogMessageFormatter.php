<?php

namespace Qu1eeeOJ\LaravelLogger\Formatters;

use Qu1eeeOJ\LaravelLogger\Interfaces\FormatterInterface;
use Qu1eeeOJ\LaravelLogger\Traits\MessageWithPrefix;

class LogMessageFormatter implements FormatterInterface
{
    use MessageWithPrefix;

    /**
     * Format the message text
     *
     * @param string $message
     *
     * @return string
     */
    public function getFormattedMessage(string $message): string
    {
        return empty($this->getPrefix())
            ? $message
            : sprintf('%s: %s', $this->getPrefix(), $message);
    }
}
