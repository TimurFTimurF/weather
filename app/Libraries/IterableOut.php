<?php

namespace App\Libraries;

use App\Interfaces\WayOutInterface;
use Illuminate\Console\Command;

class IterableOut implements WayOutInterface
{
    /**
     * @var Command
     */
    private $command;

    private $items = 0;

    /**
     * IterableOut constructor.
     *
     * @param Command $command
     */
    public function __construct(Command $command)
    {
        $this->command = $command;
    }

    /**
     * Processing each record
     *
     * @param string $item
     * @return void
     */
    public function iteration(string $item): void
    {
        $this->items++;
        $this->command->line('Processed city ' . $item);
    }

    /**
     * Processing all records
     *
     * @return void
     */
    public function send(): void
    {
        if (!$this->items) {
            $this->command->line('Sorry, data unavailable, please check settings or try again later');
        }
    }
}
