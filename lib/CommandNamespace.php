<?php

declare(strict_types=1);

namespace Clipper;

class CommandNamespace
{
    /** @var string */
    protected string $name;

    /** @var array */
    protected array $controllers = [];

    /**
     * CommandNamespace constructor
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Load namespace controllers
     *
     * @param string $commandsPath
     * @return array
     */
    public function loadControllers(string $commandsPath): array
    {
        foreach (glob(sprintf("%s/%s/*Controller.php", $commandsPath, $this->getName())) as $controllerFile) {
            $this->loadCommandMap($controllerFile);
        }
        return $this->getControllers();
    }

    /** @return array */
    public function getControllers(): array
    {
        return $this->controllers;
    }

    /**
     * @param string $commandName
     * @return CommandController|null
     */
    public function getController(string $commandName): ?CommandController
    {
        return $this->controllers[$commandName] ?? null;
    }

    /** @param string $controllerFile */
    protected function loadCommandMap(string $controllerFile): void
    {
        $filename = basename($controllerFile);

        $controllerClass = str_replace('.php', '', $filename);
        $commandName = strtolower(str_replace('Controller', '', $controllerClass));
        $fullClassName = sprintf("Console\\Command\\%s\\%s", $this->getName(), $controllerClass);

        /** @var CommandController $controller */
        $controller = new $fullClassName();
        $this->controllers[$commandName] = $controller;
    }
}
