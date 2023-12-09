<?php

declare(strict_types=1);

namespace Console\Command;

use Clipper\CommandController;
use Clipper\Console;

class HelloController extends CommandController
{
    public function run($argv): void
    {
        $name = $argv[2] ?? "World";
        Console::print("Hello $name!!!");
    }
}
