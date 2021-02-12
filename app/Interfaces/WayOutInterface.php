<?php

namespace App\Interfaces;

interface WayOutInterface
{
    public function iteration(string $item);

    public function send();
}
