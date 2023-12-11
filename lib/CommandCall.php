<?php

declare(strict_types=1);

namespace Clipper;

class CommandCall
{
    /** @var string */
    public ?string $command;

    /** @var string*/
    public string $subcommand;

    /** @var array */
    public array $args = [];

    /** @var array */
    public array $params = [];

    /**
     * CommandCall constructor.
     * @param array $argv
     * @return void
     */
    public function __construct(array $argv)
    {
        $this->args = $argv;
        $this->command = $argv[1] ?? null;
        $this->subcommand = $argv[2] ?? 'default';
        $this->loadParams($argv);
    }

    /** @param array $args */
    protected function loadParams(array $args): void
    {
        foreach ($args as $arg) {
            $pair = explode('=', $arg);
            if (2 == count($pair)) {
                $this->params[$pair[0]] = $pair[1];
            }
        }
    }

    /**
     * @param string $param
     * @return boolean
     */
    public function hasParam(string $param): bool
    {
        return isset($this->params[$param]);
    }

    /**
     * @param string $param
     * @return string|null
     */
    public function getParam(string $param): ?string
    {
        return $this->hasParam($param) ? $this->params[$param] : null;
    }
}
