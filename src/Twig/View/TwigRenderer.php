<?php

namespace ZfExtra\Twig\View;

use Twig_Environment;
use Twig_Loader_Chain;
use Zend\View\Exception\DomainException;
use Zend\View\Model\ModelInterface;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\RendererInterface;
use Zend\View\Renderer\TreeRendererInterface;
use Zend\View\Resolver\ResolverInterface;
use Zend\View\View;

class TwigRenderer implements RendererInterface, TreeRendererInterface
{

    const INHERITANCE_ZEND = 'zend';
    const INHERITANCE_EXTEND = 'extend';

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
    View $view, Twig_Loader_Chain $loader, Twig_Environment $enviroment, TwigResolver $resolver, $layoutInheritance = self::INHERITANCE_ZEND
    )
    {
        $this->view = $view;
        $this->loader = $loader;
        $this->environment = $enviroment;
        $this->resolver = $resolver;
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
        return $template->render($model->getVariables());
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
                /** @var ViewModel $child */
                if ($this->canRender($child->getTemplate())) {
                    $template = $this->resolver->resolve($child->getTemplate(), $this);
                    $values[$child->captureTo()] = $template->render((array) $child->getVariables());
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

}
