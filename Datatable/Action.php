<?php

namespace DutchBridge\DatatableBundle\Datatable;

/**
 * Class Field
 *
 * @package DutchBridge\DatatableBundle\Datatable
 */
class Action
{
    /**
     * Type of action. Can be route, url or dropdown.
     *
     * @var string
     */
    protected $type;

    /**
     * The class it gets
     *
     * @var string
     */
    protected $class;

    /**
     * Icon for the action
     *
     * @var string
     */
    protected $icon;

    /**
     * Label of the action
     *
     * @var string
     */
    protected $label;

    /**
     * Route
     *
     * @var boolean
     */
    protected $route;

    /**
     * routeParameters
     *
     * @var string
     */
    protected $routeParameters;

    /**
     * attributes
     *
     * @var string
     */
    protected $attributes;

    public function __construct()
    {
        $this->class = 'btn btn-sm btn-default';
    }
    //-------------------------------------------------
    // Public
    //-------------------------------------------------

    /**
     * Gets the Type of action. Can be route, url or dropdown..
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the Type of action. Can be route, url or dropdown..
     *
     * @param string $type the type
     *
     * @return self
     */
    public function setType($type)
    {
        if ($type !== 'route' && $type !== 'url' && $type !== 'dropdown') {
            return $this;
        }

        $this->type = $type;

        return $this;
    }

    /**
     * Gets the The class it gets.
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Sets the The class it gets.
     *
     * @param string $class the class
     *
     * @return self
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Gets the Icon for the action.
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Sets the Icon for the action.
     *
     * @param string $icon the icon
     *
     * @return self
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Gets the Label of the action.
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Sets the Label of the action.
     *
     * @param string $label the label
     *
     * @return self
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Gets the Route.
     *
     * @return boolean
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Sets the Route.
     *
     * @param boolean $route the route
     *
     * @return self
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Gets the routeParameters.
     *
     * @return string
     */
    public function getRouteParameters($type='fields')
    {
        if (isSet($this->routeParameters[$type])) {
            return $this->routeParameters[$type];

        }

        return array();
    }

    /**
     * Sets the routeParameters.
     *
     * @param string $routeParameters the routeParameters
     *
     * @return self
     */
    public function addRouteParameter($name, $parameter)
    {

        if (isSet($parameter['field'])) {
            $this->routeParameters['fields'][$name] = $parameter['field'];
        }

        if (isSet($parameter['value'])) {
            $this->routeParameters['values'][$name] = $parameter['value'];
        }

        return $this;
    }

    /**
     * attributes
     *
     * @return string
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * attributes
     *
     * @param string $attributes the attributes
     *
     * @return self
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }
}
