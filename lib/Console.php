<?php

declare(strict_types=1);

namespace Clipper;

class Console
{
    /** @var CliPrinter */
    public static CliPrinter $printer;

    /** @var CommandRegistry */
    protected CommandRegistry $commandRegistry;

    /** @var string */
    protected string $appSignature;

    /**
     * Console constructor
     *
     * @return void
     */
    public function __construct()
    {
        self::$printer = new CliPrinter();
        $this->commandRegistry = new CommandRegistry(basename(__DIR__) . '/console/Command');
    }

    /**
     * @param string $appSignature
     * @return void
     */
    public function setSignature(string $appSignature): void
    {
        $this->appSignature = $appSignature;
    }

    /** @return string */
    public function getSignature(): string
    {
        return $this->appSignature;
    }

    public function printSignature(): void
    {
        Console::print(sprintf("usage: %s", $this->getSignature()));
    }

    /**
     * @param string $name
     * @param callable $callback
     * @return void
     */
    public function registerCommand(string $name, callable $callback): void
    {
        $this->commandRegistry->registerCommand($name, $callback);
    }

    /** @param array $argv */
    public function runCommand(array $argv = []): void
    {
        $input = new CommandCall($argv);
        if (count($input->args) < 2) {
            $this->printSignature();
            exit;
        }
        $controller = $this->commandRegistry->getCallableController($input->command, $input->subcommand);

        if ($controller instanceof CommandController) {
            $controller->boot($this);
            $controller->run($input);
            $controller->teardown();
            exit;
        }
        $this->runSingle($input);
    }

    protected function runSingle(CommandCall $input)
    {
        try {
            $callback = $this->commandRegistry->getCallback($input->command);
            call_user_func($callback, $input);
        } catch (\Exception $e) {
            Console::print(sprintf("ERROR: %s", $e->getMessage()));
            $this->printSignature();
            exit;
        }
    }

    public static function print(string $message): void
    {
        self::$printer->print($message);
    }
}
