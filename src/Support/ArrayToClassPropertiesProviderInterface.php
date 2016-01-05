<?php

namespace ZfExtra\Support;

/**
 *
 * @author alex
 */
interface ArrayToClassPropertiesProviderInterface
{

    /**
     * Populates class from an array,
     * First, tries to use class methods, falls back to class property.
     * Finally, throws Exception, if $strict = true.
     * 
     * @param array $data
     * @param bool $strict
     * @param array $mapping
     * @throws Exception if $strict = true
     */
    public function arrayToClassProperties(array $data, $strict = false, array $mapping = array());
}
