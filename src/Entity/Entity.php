<?php

namespace ZfExtra\Entity;

use ZfExtra\Support\ArrayToClassPropertiesProviderInterface;
use ZfExtra\Support\ArrayToClassPropertiesTrait;

class Entity implements EntityInterface, ArrayToClassPropertiesProviderInterface
{

    use ArrayToClassPropertiesTrait;

    public function import(array $data, $strict = false)
    {
        $this->arrayToClassProperties($data, $strict);
    }

}
