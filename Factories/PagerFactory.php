<?php

namespace PunkAve\PagerBundle\Factories;

class PagerFactory
{

    public function __construct($router)
    {
        $this->router = $router;
    }

    /**
     * Instanciates a new Pager of the type specified.
     * Defaults to DoctrineORM
     *
     * @return PunkAve\PagerBundle\Interfaces\Pager
     */
    public function getPager($type = "DoctrineORM")
    {
        switch($type)
        {
            case "DoctrineORM":
            default:
                return $this->instanciatePager('PunkAve\PagerBundle\DoctrineORM\Pager');
        }
    }

    /**
     * Returns a new pager with its dependencies injected.
     *
     * @return PunkAve\PagerBundle\Interfaces\Pager
     */
    public function instanciatePager($class = 'PunkAve\PagerBundle\DoctrineORM\Pager')
    {
        $pager = new $class();
        $pager->setRouter($this->router);

        return $pager;
    }
    
}
