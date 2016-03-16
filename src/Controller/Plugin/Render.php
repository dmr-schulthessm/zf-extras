<?php

namespace ZfExtra\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\View\Model\ViewModel;
use ZfExtra\View\DirectRenderer;

/**
 * Provides access to view renderer from controller.
 * 
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
class Render extends AbstractPlugin
{

    /**
     *
     * @var DirectRenderer
     */
    protected $directRenderer;

    /**
     * 
     * @param DirectRenderer $directRenderer
     */
    public function __construct(DirectRenderer $directRenderer)
    {
        $this->directRenderer = $directRenderer;
    }

    /**
     * @return string
     */
    public function __invoke(ViewModel $model)
    {
        return $this->directRenderer->render($model);
    }

}
