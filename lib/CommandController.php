<?php

declare(strict_types=1);

namespace Clipper;

abstract class CommandController
{
    /** @var Console */
    protected Console $console;

    /** @var CommandCall */
    protected CommandCall $input;

    /**
     * Command Logic.
     * @return void
     */
    abstract public function handle(): void;

    /**
     * Called before `run`.
     * @param Console $console
     * @return void
     */
    public function boot(Console $console)
    {
        $this->console = $console;
    }

    /**
     * @param CommandCall $input
     * @return void
     */
    public function run(CommandCall $input): void
    {
        $this->input = $input;
    }

    /**
     * Called when `run` is successfully finished.
     * @return void
     */
    public function teardown()
    {
        //
    }

    /** @return array */
    protected function getArgs(): array
    {
        return $this->input->args;
    }

    /** @rerurn array */
    protected function getParams(): array
    {
        return $this->input->params;
    }

    /**
     * @param string $param
     * @return boolean
     */
    protected function hasParam(string $param): bool
    {
        return $this->input->hasParam($param);
    }

    /**
     * @param string $param
     * @return string|null
     */
    protected function getParam(string $param): ?string
    {
        return $this->input->getParam($param);
    }

    /** @return Console */
    protected function getConsole(): Console
    {
        return $this->console;
    }
}
