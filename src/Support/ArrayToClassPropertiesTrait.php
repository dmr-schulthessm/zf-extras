<?php

namespace ZfExtra\Support;

use Exception;

/**
 *
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
trait ArrayToClassPropertiesTrait
{
    public function arrayToClassProperties(array $data, $strict = false)
    {
        foreach ($data as $property => $value) {
            $method = 'set' . $property;
            if (method_exists($this, $method)) {
                call_user_func_array([$this, $method], [$value]);
            } elseif (property_exists($this, $property)) {
                $this->$property = $value;
            } elseif ($strict) {
                throw new Exception(sprintf('Import error: cannot find method "%s" or property "%s"', $method, $property));
            }
        }
    }
}
