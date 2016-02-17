<?php

namespace ZfExtra\Intl\Translator\Loader;

use Symfony\Component\Yaml\Yaml;
use Zend\I18n\Translator\Loader\AbstractFileLoader;
use Zend\I18n\Translator\TextDomain;

class YmlLoader extends AbstractFileLoader
{

    public function load($locale, $filename)
    {
        $resolvedIncludePath = $this->resolveViaIncludePath($filename);
        $messages = Yaml::parse($resolvedIncludePath);
        $textDomain = new TextDomain($messages);
        if (array_key_exists('', $textDomain)) {
            if (isset($textDomain['']['plural_forms'])) {
                $textDomain->setPluralRule(
                    PluralRule::fromString($textDomain['']['plural_forms'])
                );
            }
            unset($textDomain['']);
        }
        return $textDomain;
    }

}
