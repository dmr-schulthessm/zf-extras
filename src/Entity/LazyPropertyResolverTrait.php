<?php

namespace ZfExtra\Entity;

use Exception;

/**
 *
 * @author Alex Oleshkevich <alex.oleshkevich@muehlemann-popp.ch>
 */
trait LazyPropertyResolverTrait
{
    /**
     * Resolves property value if callable.
     * 
     * @param callable|null|int $property
     * @return mixed
     * @throws Exception
     */
    public function resolveLazyProperty(&$property)
    {
        if (is_callable($property)) {
            $property = call_user_func_array($property, []);
            return $property;
        }
        
        if (is_numeric($property)) {
            return $property;
        }
        
        if (null === $property) {
            throw new Exception(__TRAIT__ . ': property is null therefore not callable. Direct access to ' . __METHOD__ . ' of newly created entity?');
        }
        
        throw new Exception(__TRAIT__ . ': unable to lazy load property. Not callable');
    }
}
