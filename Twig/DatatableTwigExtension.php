<?php

namespace DutchBridge\DatatableBundle\Twig;

use Twig_Extension;
use Twig_SimpleFunction;
use DutchBridge\DatatableBundle\Datatable\AbstractDatatableView;

/**
 * Class DatatableTwigExtension
 *
 * @package DutchBridge\DatatableBundle\Twig
 */
class DatatableTwigExtension extends Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sg_datatables_twig_extension';
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('datatable_render', array($this, 'datatableRender'), array('is_safe' => array('all')))
        );
    }

    /**
     * Renders the template.
     *
     * @param AbstractDatatableView $datatable
     *
     * @return string
     */
    public function datatableRender(AbstractDatatableView $datatable)
    {
        return $datatable->createView();
    }
}
