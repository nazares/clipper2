<?php

declare(strict_types=1);

namespace Clipper;

abstract class CommandController
{
    protected Console $console;

    abstract public function run($argv);

    public function __construct(Console $console)
    {
        $this->console = $console;
    }

    protected function getConsole(): Console
    {
        return $this->console;
    }
}
