<?php

namespace Clipper;

class CommandRegistry
{
    protected array $registry = [];

    protected array $controllers = [];

    public function registerController(string $commandName, CommandController $controller): void
    {
        $this->controllers = [$commandName => $controller];
    }

    public function registerCommand(string $name, callable $callback)
    {
        $this->registry[$name] = $callback;
    }

    public function getController(string $command): ?CommandController
    {
        return $this->controllers[$command] ?? null;
    }

    public function getCommand(string $command): ?callable
    {
        return $this->registry[$command] ?? null;
    }

    public function getCallback(string $commandName)
    {
        $controller = $this->getController($commandName);

        if ($controller instanceof CommandController) {
            return [$controller, 'run'];
        }

        $command = $this->getCommand($commandName);

        if (null === $command) {
            throw new \Exception("Command \"$commandName\" not found.");
        }
        return $command;
    }
}
