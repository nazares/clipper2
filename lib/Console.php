<?php

declare(strict_types=1);

namespace Clipper;

class Console
{
    public static CliPrinter $printer;

    protected CommandRegistry $commandRegistry;


    public function __construct()
    {
        self::$printer = new CliPrinter();
        $this->commandRegistry = new CommandRegistry();
    }

    public function registerController(string $name, CommandController $controller)
    {
        $this->commandRegistry->registerController($name, $controller);
    }

    public function registerCommand(string $name, callable $callback): void
    {
        $this->commandRegistry->registerCommand($name, $callback);
    }

    public function runCommand(array $argv = [], $defaultCommand = 'help'): void
    {
        $commandName = $argv[1] ?? $defaultCommand;
        try {
            call_user_func($this->commandRegistry->getCallback($commandName), $argv);
        } catch (\Exception $e) {
            Console::print("ERROR: " . $e->getMessage());
        }
    }

    public static function print(string $message): void
    {
        self::$printer->print($message);
    }
}
