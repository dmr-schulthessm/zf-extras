<?php

namespace ZfExtra\Entity;

use ZfExtra\Support\ArrayToClassPropertiesTrait;

abstract class AbstractEntity
{

    use ArrayToClassPropertiesTrait;

    public function import(array $data, $strict = false)
    {
        $this->arrayToClassProperties($data, $strict);
    }

}
