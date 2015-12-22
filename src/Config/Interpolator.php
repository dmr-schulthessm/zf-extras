<?php

namespace ZfExtra\Config;

/**
 * Class to replace variables in arrays with theirs values.
 * 
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
class Interpolator
{
    use ValueFinderTrait;
    
    /**
     *
     * @var array
     */
    protected $variables = array();
    
    /**
     * 
     * @param mixed $value
     * @return type
     */
    protected function process($value)
    {
        if (is_array($value)) {
            foreach ($value as &$val) {
                $val = $this->process($val);
            }
            return $value;
        } else {
            return $this->apply($value);
        }
    }

    /**
     * Interpolate variables in $value string.
     * 
     * @param string $value
     * @param array $variables
     * @return string
     */
    protected function apply($value)
    {
        $doInterpolate = function ($value) {
            $name = substr($value, 1);
            return $this->find($this->variables, $name);
        };

        if (!is_string($value) || strlen($value) == 0) {
            return $value;
        }
        
        // handle ! modifier for booleans
        if ($value[0] == '!' && $value[1] == '$') {
            return !$doInterpolate(substr($value, 1));
        }

        // replace variables with content
        if ($value[0] == '$') {
            return $doInterpolate($value);
        }
        return $value;
    }

    /**
     * Entry point.
     * 
     * @param array $data
     * @param array $variables
     * @return array
     */
    public function interpolate(array $data, array $variables = array())
    {
        $this->variables = $variables;
        
        return array_map(function ($value) {
            return $this->process($value);
        }, $data);
    }

}
