<?php

namespace ZfExtra\Mvc\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Session\Container;
use Zend\Session\ManagerInterface;

/**
 * A session controller plugin.
 * 
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 */
class Session extends AbstractPlugin
{

    /**
     *
     * @var array
     */
    protected $containers = array();

    /**
     * @return Container
     */
    public function __invoke($containerName = 'default', ManagerInterface $manager = null)
    {
        if (!isset($this->containers[$containerName])) {
            $this->containers[$containerName] = new Container($containerName, $manager);
        }
        return $this->containers[$containerName];
    }

}
