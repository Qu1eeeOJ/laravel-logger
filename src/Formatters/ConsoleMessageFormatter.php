<?php

namespace Qu1eeeOJ\LaravelLogger\Formatters;

use Illuminate\Support\Carbon;
use Qu1eeeOJ\LaravelLogger\Interfaces\FormatterWithStyleInterface;
use Qu1eeeOJ\LaravelLogger\Traits\MessageWithPrefix;

class ConsoleMessageFormatter implements FormatterWithStyleInterface
{
    use MessageWithPrefix;

    /**
     * @var string
     */
    private string $style = '';

    /**
     * Set style
     *
     * @param string $style
     *
     * @return static
     */
    public function setStyle(string $style): static
    {
        $this->style = $style;

        return $this;
    }

    /**
     * Format the message text
     *
     * @param string $message
     *
     * @return string
     */
    public function getFormattedMessage(string $message): string
    {
        return sprintf(
            '<%s>[%s] %s: %s: %s</%s>',
            $this->style,
            Carbon::now()->toDateTimeLocalString(),
            mb_strtoupper($this->style),
            $this->getPrefix(),
            $message,
            $this->style
        );
    }
}
