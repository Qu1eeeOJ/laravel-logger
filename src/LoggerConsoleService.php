<?php

namespace Qu1eeeOJ\LaravelLogger;

use Illuminate\Support\Facades\App;
use Qu1eeeOJ\LaravelLogger\Exceptions\StyleNotFoundException;
use Qu1eeeOJ\LaravelLogger\Formatters\ConsoleMessageFormatter;
use Qu1eeeOJ\LaravelLogger\Interfaces\FormatterWithStyleInterface;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * @method void info(string $message)
 * @method void error(string $message)
 * @method void warning(string $message)
 * @method void debug(string $message)
 */
class LoggerConsoleService
{
    /**
     * @var ConsoleOutput
     */
    private readonly ConsoleOutput $console;

    /**
     * We are casting the style of operation from laravel logger for console output
     *
     * @var array<string, string>
     */
     private array $styles = [
        'info' => 'info',
        'error' => 'error',
        'warning' => 'warn',
        'debug' => 'info'
    ];

    /**
     * LoggerConsoleService constructor
     *
     * @param string|array|null $prefix
     * @param FormatterWithStyleInterface $formatter
     */
    public function __construct(
        protected readonly string|array|null $prefix = null,
        public readonly FormatterWithStyleInterface $formatter = new ConsoleMessageFormatter()
    )
    {
        $this->console = App::make(ConsoleOutput::class);

        // if the prefix is not null, then we put the prefix
        if (! is_null($this->prefix)) {
            $this->formatter->setPrefix($this->prefix);
        }
    }

    /**
     * Magic method
     *
     * @param string $style
     * @param array $arguments
     *
     * @return void
     *
     * @throws StyleNotFoundException
     */
    public function __call(string $style, array $arguments): void
    {
        $this->writeln($style, $arguments[0]);
    }

    /**
     * Writing to console output with new line
     *
     * @param string $style
     * @param string $message
     *
     * @return void
     *
     * @throws StyleNotFoundException
     */
    public function writeln(string $style, string $message): void
    {
        $this->write($style, $message, true);
    }

    /**
     * Writing to console output
     *
     * @param string $style
     * @param string $message
     * @param bool $newLine
     *
     * @return void
     *
     * @throws StyleNotFoundException
     */
    public function write(string $style, string $message, bool $newLine = false): void
    {
        // Check style for console output
        if (! array_key_exists($style, $this->styles)) {
            throw new StyleNotFoundException(sprintf("Style [%s] not found in console styles", $style));
        }

        // Setting the style to the formatter
        $this->formatter->setStyle($this->styles[$style]);

        $this->console->write($this->formatter->getFormattedMessage($message), $newLine);
    }
}
