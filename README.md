# PHP LARAVEL LOGGER
This library provides the use of laravel logger along with the output of information to the console.

***This package can be useful when running laravel commands***

## Installation
```sh
composer require qu1eeeoj/laravel-logger
```

## Example
```php
<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Qu1eeeOJ\LaravelLogger\LoggerService;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create user with admin privileges';

    /**
     * CreateUserCommand constructor
     *
     * @param LoggerService $logger - Logger with output console
     */
    public function __construct(private readonly LoggerService $logger = new LoggerService('daily'))
    {
        // Call parent constructor
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        // Create user
        $user = User::query()->create([]);
        $this->logger->info('User created successfully');

        // Adding admin privileges
        $user->permissions()->setRole('admin');
        $this->logger->info('Now user has admin privileges');

        return Command::SUCCESS;
    }
}
```

## Methods - LoggerService
- alert(string $message): void
- critical(string $message): void
- debug(string $message): void - works with the console, but in working mode it does not write information to the log, but only the console!
- emergency(string $message): void
- error(string $message): void - works with console
- info(string $message): void - works with console
- notice(string $message): void
- warning(string $message): void - works with console
- withConsoleLogger(): bool - Determine whether the console logger is used
