<?php
namespace ZfExtra\Config;

use ZfExtra\Exception\MissingConfigParamException;

trait ValueFinderTrait
{
    /**
     * Try to find config option by $path othwerwise return $default.
     * 
     * @param string $path
     * @param mixed $default
     * @param bool $strict Throw and exception if key is not set.
     * @return mxied
     */
    public function find(array $dataset, $path, $default = null, $strict = false)
    {
        $paths = explode('.', $path);
        if (count($paths) == 1) {
            $key = array_shift($paths);
            if (array_key_exists($key, $dataset)) {
                return $dataset[$key];
            } else {
                if ($strict) {
                    throw new MissingConfigParamException('Config path "' . $path . '" is not set.');
                } else {
                    return $default;
                }
            }
        }

        $current = $dataset;
        foreach ($paths as $path) {
            if (!array_key_exists($path, (array) $current)) {
                if ($strict) {
                    throw new MissingConfigParamException('Config path "' . $path . '" is not set.');
                } else {
                    return $default;
                }
            }
            $current = $current[$path];
        }
        return $current;
    }
}
