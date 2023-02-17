<?php

namespace Qu1eeeOJ\LaravelLogger;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;
use Qu1eeeOJ\LaravelLogger\Exceptions\MethodNotFoundException;
use Qu1eeeOJ\LaravelLogger\Formatters\LogMessageFormatter;
use Qu1eeeOJ\LaravelLogger\Interfaces\FormatterInterface;

/**
 * @method void alert(string $message)
 * @method void critical(string $message)
 * @method void debug(string $message)
 * @method void emergency(string $message)
 * @method void error(string $message)
 * @method void info(string $message)
 * @method void notice(string $message)
 * @method void warning(string $message)
 */
class LoggerService
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var null|LoggerConsoleService
     */
    private ?LoggerConsoleService $console = null;

    /**
     * The available methods are provided by the laravel registrar
     *
     * @var array
     */
    private array $methods = ['alert', 'critical', 'debug', 'emergency', 'error', 'info', 'notice', 'warning'];

    /**
     * LoggerService constructor
     *
     * @param string $channel
     * @param string|array|null $prefix
     * @param bool $withConsole
     * @param FormatterInterface $formatter
     */
    public function __construct(
        public readonly string $channel,
        public readonly string|array|null $prefix = null,
        bool $withConsole = true,
        public readonly FormatterInterface $formatter = new LogMessageFormatter()
    )
    {
        $this->logger = Log::channel($this->channel);

        if (! is_null($this->prefix)) {
            $this->formatter->setPrefix($this->prefix);
        }

        // If you need to run the console logger,
        // then we also check the launch in php-cli mode
        if ($withConsole && $this->isCliMode()) {
            $this->console = App::make(LoggerConsoleService::class, ['prefix' => $this->prefix]);
        }
    }

    /**
     * Magic method
     *
     * @param string $name
     * @param array $arguments
     *
     * @return void
     *
     * @throws MethodNotFoundException
     */
    public function __call(string $name, array $arguments): void
    {
        // Check method in available methods
        if (! array_key_exists($name, $this->methods)) {
            throw new MethodNotFoundException(
                sprintf(
                    'Method [%s] not found. Available methods: [%s]',
                    $name,
                    implode(',', $this->methods)
                )
            );
        }

        // If console logger enabled
        if ($this->withConsoleLogger()) {
            call_user_func_array([$this->console, $name], [$arguments[0]]);
        }

        // Debug messages in production mode do not show
        if (App::isProduction() && $name == 'debug') {
            return;
        }

        // Formatting message
        $message = $this->formatter->getFormattedMessage($arguments[0]);

        call_user_func_array([$this->logger, $name], [$message]);
    }

    /**
     * Determine whether the console logger is used
     *
     * @return bool
     */
    public function withConsoleLogger(): bool
    {
        return ! is_null($this->console);
    }

    /**
     * Set prefix
     *
     * @param string|array $prefix
     *
     * @return LoggerService
     */
    public function setPrefix(string|array $prefix): LoggerService
    {
        // Set prefix for log
        $this->formatter->setPrefix($prefix);

        // Set prefix for console log
        if ($this->withConsoleLogger()) {
            $this->console->formatter->setPrefix($prefix);
        }

        return $this;
    }

    /**
     * Add text to the beginning of the prefix
     *
     * @param string $text
     *
     * @return LoggerService
     */
    public function addTextToBeginningPrefix(string $text): LoggerService
    {
        // Add text to log
        $this->formatter->addTextToBeginningPrefix($text);

        // Add text to console log
        if ($this->withConsoleLogger()) {
            $this->console->formatter->addTextToBeginningPrefix($text);
        }

        return $this;
    }

    /**
     * Add text to the end of the prefix
     *
     * @param string $text
     *
     * @return LoggerService
     */
    public function addTextToEndPrefix(string $text): LoggerService
    {
        // Add text to log
        $this->formatter->addTextToEndPrefix($text);

        // Add text to console log
        if ($this->withConsoleLogger()) {
            $this->console->formatter->addTextToEndPrefix($text);
        }

        return $this;
    }

    /**
     * Determine php cli mode
     *
     * @return bool
     */
    private function isCliMode(): bool
    {
        return php_sapi_name() === 'cli';
    }
}
