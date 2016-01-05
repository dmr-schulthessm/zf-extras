<?php

namespace ZfExtra\Support;

use Exception;

/**
 *
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
trait ArrayToClassPropertiesTrait
{
    /**
     * Populates class from an array,
     * First, tries to use class methods, falls back to class property.
     * Finally, throws Exception, if $strict = true.
     * 
     * @param array $data
     * @param bool $strict
     * @param array $mapping Map array key to object property
     * @throws Exception if $strict = true
     */
    public function arrayToClassProperties(array $data, $strict = false, array $mapping = array())
    {
        foreach ($data as $property => $value) {
            if (isset($mapping[$property])) {
                $property = $mapping[$property];
            }
            
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
