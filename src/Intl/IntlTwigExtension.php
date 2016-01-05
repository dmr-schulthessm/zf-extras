<?php

namespace ZfExtra\Intl;

use Twig_Extension;
use Twig_SimpleFilter;
use Twig_SimpleFunction;
use Zend\I18n\Translator\TranslatorInterface;

class IntlTwigExtension extends Twig_Extension
{

    /**
     *
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * 
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function getFilters()
    {
        return array(
            new Twig_SimpleFilter('trans', [$this, 'trans'])
        );
    }

    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('trans', [$this, 'trans']),
            new Twig_SimpleFunction('transPlural', [$this, 'transPlural']),
        );
    }

    public function getName()
    {
        return 'intl';
    }

    public function trans($message, $textDomain = 'default', $locale = null)
    {
        return $this->translator->translate($message, $textDomain, $locale);
    }

    public function transPlural($singular, $plural, $number, $textDomain = 'default', $locale = null)
    {
        return $this->translator->translatePlural($singular, $plural, $number, $textDomain, $locale);
    }

}
