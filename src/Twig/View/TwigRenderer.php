<?php

namespace ZfExtra\Twig\View;

use Twig_Environment;
use Twig_Loader_Chain;
use Twig_TemplateInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\Log\Logger;
use Zend\View\Exception\DomainException;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ModelInterface;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Renderer\RendererInterface;
use Zend\View\Renderer\TreeRendererInterface;
use Zend\View\Resolver\ResolverInterface;
use Zend\View\View;
use ZfExtra\Log\EventListener\LogEventListener;
use ZfExtra\Log\LogEvent;

class TwigRenderer implements RendererInterface, TreeRendererInterface, EventManagerAwareInterface
{

    use \Zend\EventManager\EventManagerAwareTrait;

    const INHERITANCE_ZEND = 'zend';
    const INHERITANCE_EXTEND = 'extend';

    protected $eventIdentifier = LogEventListener::LOG_PROVIDER;

    /**
     * @var HelperPluginManager
     */
    protected $helperPluginManager;

    /**
     * @var array Cache for the plugin call
     */
    private $__pluginCache = array();

    /**
     *
     * @var bool
     */
    protected $canRenderTrees = true;

    /**
     *
     * @var View
     */
    protected $view;

    /**
     *
     * @var Twig_Loader_Chain
     */
    protected $loader;

    /**
     *
     * @var Twig_Environment
     */
    protected $environment;

    /**
     *
     * @var TwigResolver
     */
    protected $resolver;

    /**
     *
     * @var PhpRenderer
     */
    protected $fallbackRenderer;

    /**
     *
     * @var string
     */
    protected $layoutInheritance = self::INHERITANCE_ZEND;

    /**
     * 
     * @param View $view
     * @param Twig_Loader_Chain $loader
     * @param Twig_Environment $enviroment
     * @param TwigResolver $resolver
     */
    public function __construct(
        View $view, 
        Twig_Loader_Chain $loader, 
        Twig_Environment $enviroment, 
        TwigResolver $resolver, 
        PhpRenderer $fallbackRenderer = null, 
        $layoutInheritance = self::INHERITANCE_ZEND
    )
    {
        $this->view = $view;
        $this->loader = $loader;
        $this->environment = $enviroment;
        $this->resolver = $resolver;
        $this->fallbackRenderer = $fallbackRenderer;
        $this->layoutInheritance = $layoutInheritance;
    }

    /**
     * 
     * @return Twig_Environment
     */
    public function getEngine()
    {
        return $this->environment;
    }

    /**
     * 
     * @param ModelInterface|string $nameOrModel
     * @param mixed $values
     * @return string
     */
    public function render($nameOrModel, $values = null)
    {
        if ($this->layoutInheritance == self::INHERITANCE_EXTEND) {
            return $this->renderStrategyExtend($nameOrModel, $values);
        } else {
            return $this->renderStrategyZend($nameOrModel, $values);
        }
    }

    /**
     * 
     * @param string|ViewModel $nameOrModel
     * @param mixed $values
     * @return string
     * @throws DomainException
     */
    public function renderStrategyExtend($nameOrModel, $values = null)
    {
        $model = null;
        if ($nameOrModel->hasChildren()) {
            foreach ($nameOrModel as $child) {
                if ($child->captureTo() == 'content') {
                    $model = $child;
                    break;
                }
            }
        }
        $template = $this->resolver->resolve($model->getTemplate(), $this);
        return $template->render((array) $model->getVariables());
    }

    /**
     * 
     * @param string|ViewModel $nameOrModel
     * @param mixed $values
     * @return string
     * @throws DomainException
     */
    public function renderStrategyZend($nameOrModel, $values = null)
    {
        $model = null;
        if ($nameOrModel instanceof ModelInterface) {
            $model = $nameOrModel;
            $nameOrModel = $model->getTemplate();
            if (empty($nameOrModel)) {
                throw new DomainException(sprintf(
                    '%s: received View Model argument, but template is empty', __METHOD__
                ));
            }
            $values = (array) $model->getVariables();
        }

        if (!$this->canRender($nameOrModel)) {
            return null;
        }

        if ($model && $this->canRenderTrees() && $model->hasChildren()) {
            if (!isset($values['content'])) {
                $values['content'] = '';
            }
            foreach ($model as $child) {
                $isTwigTemplate = false;
                try {
                    $path = $this->loader->getCacheKey($child->getTemplate());
                    $isTwigTemplate = substr($path, -5) == '.twig';
                } catch (\Exception $e) {
                    
                }

                /** @var ViewModel $child */
                if ($isTwigTemplate) {
                    /* @var $template Twig_TemplateInterface */
                    $path = $child->getTemplate();
                    $template = $this->resolver->resolve($path, $this);
                    $values[$child->captureTo()] = $template->render((array) $child->getVariables());
                    $this->getEventManager()->triggerEvent(new LogEvent($this, 'render: ' . $path, Logger::INFO));
                } else {
                    $values[$child->captureTo()] = $this->fallbackRenderer->render($child->getTemplate(), (array) $child->getVariables());
                }
                $child->setOption('has_parent', true);
            }
        }

        return $this->resolver->resolve($model->getTemplate(), $this)->render((array) $values);
    }

    /**
     * 
     * @param ResolverInterface $resolver
     * @return TwigRenderer
     */
    public function setResolver(ResolverInterface $resolver)
    {
        $this->resolver = $resolver;
        return $this;
    }

    /**
     * 
     * @param string $name
     * @return bool
     */
    public function canRender($name)
    {
        return true;
    }

    /**
     * 
     * @param bool $canRenderTrees
     */
    public function setCanRenderTrees($canRenderTrees)
    {
        $this->canRenderTrees = $canRenderTrees;
    }

    /**
     * 
     * @return bool
     */
    public function canRenderTrees()
    {
        return $this->canRenderTrees;
    }

    /**
     * @param HelperPluginManager $helperPluginManager
     * @return TwigRenderer
     */
    public function setHelperPluginManager(HelperPluginManager $helperPluginManager)
    {
        $helperPluginManager->setRenderer($this);
        $this->helperPluginManager = $helperPluginManager;
        return $this;
    }

    /**
     * Overloading: proxy to helpers
     *
     * Proxies to the attached plugin manager to retrieve, return, and potentially
     * execute helpers.
     *
     * * If the helper does not define __invoke, it will be returned
     * * If the helper does define __invoke, it will be called as a functor
     *
     * @param  string $method
     * @param  array $argv
     * @return mixed
     */
    public function __call($method, $argv)
    {
        die(__METHOD__);
        if (!isset($this->__pluginCache[$method])) {
            $this->__pluginCache[$method] = $this->plugin($method);
        }
        if (is_callable($this->__pluginCache[$method])) {
            return call_user_func_array($this->__pluginCache[$method], $argv);
        }
        return $this->__pluginCache[$method];
    }

    /**
     * Get plugin instance, proxy to HelperPluginManager::get
     *
     * @param  string     $name Name of plugin to return
     * @param  null|array $options Options to pass to plugin constructor (if not already instantiated)
     * @return AbstractHelper
     */
    public function plugin($name, array $options = null)
    {
        var_dump($name);
        return $this->getHelperPluginManager()
                ->setRenderer($this)
                ->get($name, $options);
    }

}
