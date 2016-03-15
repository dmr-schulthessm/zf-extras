<?php

namespace ZfExtra\Config\Reader;

use Symfony\Component\Yaml\Yaml as YamlParser;
use Zend\Config\Reader\Yaml as ZendYaml;
use Zend\Stdlib\ArrayUtils;
use Zend\View\Exception\RuntimeException;

/**
 * Yaml config reader.
 * 
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
class Yaml extends ZendYaml
{

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct([new YamlParser, 'parse']);
    }

    /**
     * Process the array for @include
     *
     * @param  array $data
     * @return array
     * @throws RuntimeException
     */
    protected function process(array $data)
    {
        $data = parent::process($data);
        foreach ($data as $key => $value) {
            if (trim($key) === '@includes') {
                if ($this->directory === null) {
                    throw new RuntimeException('Cannot process @includes statement for a json string');
                }
                unset($data[$key]);

                foreach ($value as $file) {
                    $reader = clone $this;
                    $data = ArrayUtils::merge($data, $reader->fromFile($this->directory . '/' . $file));
                }
            }

            if (trim($key) === '@optional') {
                $optionals = (array) $data[$key];
                if ($this->directory === null) {
                    throw new RuntimeException('Cannot process @optional statement for a json string');
                }
                unset($data[$key]);

                foreach ($optionals as $optional) {
                    $filename = $this->directory . '/' . $optional;
                    if (file_exists($filename)) {
                        $reader = clone $this;
                        $data = ArrayUtils::merge($data, $reader->fromFile($filename));
                    }
                }
            }

            if (trim($key) === '@include_php') {
                $optionals = (array) $data[$key];
                if ($this->directory === null) {
                    throw new RuntimeException('Cannot process @optional statement for a json string');
                }
                unset($data[$key]);

                foreach ($optionals as $optional) {
                    $filename = $this->directory . '/' . $optional;
                    if (file_exists($filename)) {
                        $data = ArrayUtils::merge($data, include $filename);
                    }
                }
            }
        }
        
        return $data;
    }

}
