<?php

declare(strict_types=1);

namespace Clipper;

class Console
{
    public static CliPrinter $printer;

    protected array $registry = [];


    public function __construct()
    {
        self::$printer = new CliPrinter();
    }

    public function registerCommand(string $name, callable $callback): void
    {
        $this->registry[$name] = $callback;
    }

    public function getCommand(string $command): ?callable
    {
        return $this->registry[$command] ?? null;
    }

    public function runCommand(array $argv)
    {

        $commandName = $argv[1] ?? 'help';
        $command = $this->getCommand($commandName);
        if (null === $command) {
            self::print("ERROR: Command \"$commandName\" not found.");
            exit;
        }
        call_user_func($command, $argv);
    }

    public static function print(string $message): void
    {
        self::$printer->print($message);
    }
}
