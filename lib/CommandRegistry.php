<?php

namespace Clipper;

class CommandRegistry
{
    /** @var string */
    protected string $commandsPath;

    /** @var array */
    protected array $namespaces = [];

    /** @var array */
    protected array $defaultRegistry = [];

    /**
     * CommandRegistry constructor.
     * @param string $commandsPath
     * @return void
     */
    public function __construct(string $commandsPath)
    {
        $this->commandsPath = $commandsPath;
        $this->autoloadNamespaces();
    }

    /** @return void */
    public function autoloadNamespaces(): void
    {
        foreach (glob(sprintf("%s/*", $this->getCommandsPath()), GLOB_ONLYDIR) as $namespacePath) {
            $this->registerNamespace(basename($namespacePath));
        }
    }

    /**
     * @param string $commandNamespace
     * @return void
     */
    public function registerNamespace(string $commandNamespace): void
    {
        $namespace = new CommandNamespace($commandNamespace);
        $namespace->loadControllers($this->getCommandsPath());
        $this->namespaces[strtolower($commandNamespace)] = $namespace;
    }

    /**
     * @param string $command
     * @return CommandNamespace|null
     */
    public function getNamespace(string $command): ?CommandNamespace
    {
        return $this->namespaces[$command] ?? null;
    }

    /** @return  string*/
    public function getCommandsPath(): string
    {
        return $this->commandsPath;
    }

    /**
     * Registers an anonymous function as single command
     *
     * @param string $name
     * @param callable $callback
     * @return void
     */
    public function registerCommand(string $name, callable $callback)
    {
        $this->defaultRegistry[$name] = $callback;
    }

    /**
     * @param string $command
     * @return callable|null
     */
    public function getCommand(string $command): ?callable
    {
        return $this->defaultRegistry[$command] ?? null;
    }

    /**
     * @param string $command
     * @param string|null $subcommand
     * @return CommandController|null
     */
    public function getCallableController(string $command, ?string $subcommand = null): ?CommandController
    {
        $namespace = $this->getNamespace($command);
        if (null !== $namespace) {
            return $namespace->getController($subcommand);
        }

        return null;
    }

    /**
     * Undocumented function
     *
     * @param string $command
     * @return callable
     * @throws Exception
     */
    public function getCallback(string $command): callable
    {
        $singleCommand = $this->getCommand($command);
        if (null === $singleCommand) {
            throw new \Exception(sprintf("Command \"$command\" not found.", $command));
        }
        return $singleCommand;
    }
}
