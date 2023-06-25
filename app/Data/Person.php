<?php

namespace App\Data;

class Person
{
    var string $name;

    function __construct(string $name)
    {
        $this->name = $name;
    }
}
