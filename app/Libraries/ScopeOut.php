<?php

namespace App\Libraries;

use App\Interfaces\WayOutInterface;

class ScopeOut implements WayOutInterface
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * Processing each record
     *
     * @param string $item
     * @return void
     */
    public function iteration(string $item): void
    {
        $this->data[] = 'City ' . $item;
    }

    /**
     * Processing all records
     *
     * @return string
     */
    public function send(): string
    {
        if (count($this->data)) {
            $result = implode('<br />', $this->data);
        } else {
            $result = 'Sorry, data unavailable, please check settings or try again later';
        }

        return $result;
    }
}
