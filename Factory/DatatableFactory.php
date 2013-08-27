<?php

namespace DutchBridge\DatatableBundle\Factory;

use Twig_Environment as Twig;

/**
 * Class DatatableFactory
 *
 * @package DutchBridge\DatatableBundle\Factory
 */
class DatatableFactory
{
    /**
     * @var Twig
     */
    protected $twig;

    /**
     * Ctor.
     *
     * @param Twig $twig A Twig instance
     */
    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Returns a instance of the datatableViewClass.
     *
     * @param string $datatableViewClass The class name
     *
     * @return \DutchBridge\DatatableBundle\Datatable\AbstractDatatableView
     * @throws \Exception
     */
    public function getDatatableView($datatableViewClass)
    {
        if (!class_exists($datatableViewClass)) {
            throw new \Exception("Class {$datatableViewClass} not found.");
        }

        return new $datatableViewClass($this->twig);
    }
}
