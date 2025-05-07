<?php

namespace App\Cls;
use InvalidArgumentException;
class Error {

    public $result;

    public function __construct(private int $num) {

        if ($this->num === 0) {
            throw new \InvalidArgumentException("num is zero");
        }

        $this->result = 100/$this->num;


    }

    public function getNum() {

         return $this->result;
    }


}