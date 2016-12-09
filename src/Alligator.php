<?php

namespace ZooConsumer;

class Alligator
{

    private $name;

    /**
     * Alligator constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getName(){
        return $this->name;
    }
}