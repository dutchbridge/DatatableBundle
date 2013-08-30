<?php

namespace DutchBridge\DatatableBundle\Datatable;

use Symfony\Bridge\Twig\TwigEngine as Twig;

/**
 * Class AbstractDatatableView
 *
 * @package DutchBridge\DatatableBundle\Datatable
 */
abstract class AbstractDatatableView
{
    /**
     * A Twig instance.
     *
     * @var Twig
     */
    protected $twig;

    /**
     * The Twig template.
     *
     * @var string
     */
    protected $template;

    /**
     * The css sDom parameter for:
     *  - dataTables_length,
     *  - dataTables_filter,
     *  - dataTables_info,
     *  - pagination
     *
     * @var array
     */
    protected $sDomOptions;

    /**
     * The table id selector.
     *
     * @var string
     */
    protected $tableId;

    /**
     * Content for the table header cells.
     *
     * @var array
     */
    protected $tableHeaders;

    /**
     * The aoColumns fields.
     *
     * @var array
     */
    protected $fields;

    /**
     * The aoColumns fields.
     *
     * @var array
     */
    protected $action;

    /**
     * The sAjaxSource path.
     *
     * @var string
     */
    protected $sAjaxSource;

    /**
     * Array for route parameters.
     *
     * @var array
     */
    protected $routeParameters;

    /**
     * Array for custom options.
     *
     * @var array
     */
    protected $customizeOptions;

    //-------------------------------------------------
    // Ctor
    //-------------------------------------------------

    /**
     * Ctor.
     *
     * @param Twig $twig A Twig instance
     */
    public function __construct(Twig $twig)
    {
        $this->twig        = $twig;
        $this->template    = 'DutchBridgeDatatableBundle::default.html.twig';
        $this->sDomOptions = array(
            'sDomLength'     => 'col-sm-4',
            'sDomFilter'     => 'col-sm-8',
            'sDomInfo'       => 'col-sm-3',
            'sDomPagination' => 'col-sm-9'
        );

        $this->tableId          = 'datatable';
        $this->tableHeaders     = array();
        $this->fields           = array();
        $this->actions          = array();
        $this->sAjaxSource      = '';
        $this->showPath         = '';
        $this->editPath         = '';
        $this->deletePath       = '';
        $this->customizeOptions = array();
        $this->routeParameters  = array();

        $this->build();
    }

    //-------------------------------------------------
    // Build view
    //-------------------------------------------------

    /**
     * @return mixed
     */
    abstract public function build();

    /**
     * Set all options for the twig template.
     *
     * @return string
     */
    public function createView()
    {
        $options = array();
        $options['id']               = $this->getTableId();
        $options['sAjaxSource']      = $this->getSAjaxSource();
        $options['tableHeaders']     = $this->getTableHeaders();
        $options['sDomOptions']      = $this->getSDomOptions();
        $options['fields']           = $this->getFieldsOptions();
        $options['actions']          = $this->getActions();
        $options['customizeOptions'] = $this->getCustomizeOptions();
        $options['routeParameters']  = $this->getRouteParameters();

        return $this->twig->render($this->getTemplate(), $options);
    }

    //-------------------------------------------------
    // Field functions
    //-------------------------------------------------

    /**
     * @param Field $field
     *
     * @return AbstractDatatableView
     */
    public function addField($field)
    {
        $this->fields[] = $field;

        return $this;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Get fields options.
     *
     * @return array
     */
    private function getFieldsOptions()
    {
        $mData = array();

        /**
         * @var \DutchBridge\DatatableBundle\Datatable\Field $field
         */
        foreach ($this->fields as $field) {

            $property = array(
                'mData'                => $field->getMData(),
                'sName'                => $field->getSName(),
                'sClass'               => $field->getSClass(),
                'mRender'              => $field->getMRender(),
                'renderArray'          => $field->getRenderArray(),
                'renderArrayFieldName' => $field->getRenderArrayFieldName(),
                'sWidth'               => $field->getSWidth(),
                'bSearchable'          => $field->getBSearchable(),
                'bSortable'            => $field->getBSortable()
            );

            array_push($mData, $property);
        }

        return $mData;
    }

    //-------------------------------------------------
    // sDom functions
    //-------------------------------------------------

    /**
     * @param array $sDomOptions
     *
     * @throws \Exception
     */
    public function setSDomOptions($sDomOptions)
    {
        if (!array_key_exists('sDomLength', $sDomOptions)) {
            throw new \Exception('The option "sDomLength" must be set.');
        };

        if (!array_key_exists('sDomFilter', $sDomOptions)) {
            throw new \Exception('The option "sDomFilter" must be set.');
        };

        if (!array_key_exists('sDomInfo', $sDomOptions)) {
            throw new \Exception('The option "sDomInfo" must be set.');
        };

        if (!array_key_exists('sDomPagination', $sDomOptions)) {
            throw new \Exception('The option "sDomPagination" must be set.');
        };

        $this->sDomOptions = $sDomOptions;
    }

    /**
     * @return array
     */
    public function getSDomOptions()
    {
        return $this->sDomOptions;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * @param string $sAjaxSource
     */
    public function setSAjaxSource($sAjaxSource)
    {
        $this->sAjaxSource = $sAjaxSource;
    }

    /**
     * @return string
     */
    public function getSAjaxSource()
    {
        return $this->sAjaxSource;
    }

    /**
     * @param array $tableHeaders
     */
    public function setTableHeaders($tableHeaders)
    {
        $this->tableHeaders = $tableHeaders;
    }

    /**
     * @return array
     */
    public function getTableHeaders()
    {
        return $this->tableHeaders;
    }

    /**
     * @param string $tableId
     */
    public function setTableId($tableId)
    {
        $this->tableId = $tableId;
    }

    /**
     * @return string
     */
    public function getTableId()
    {
        return $this->tableId;
    }

    /**
     * @param Action $action
     *
     * @return AbstractDatatableView
     */
    public function addAction($action)
    {
        $this->actions[] = $action;

        return $this;
    }

    /**
     * @return array
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @param array $customizeOptions
     */
    public function setCustomizeOptions($customizeOptions)
    {
        $this->customizeOptions = $customizeOptions;
    }

    /**
     * @return array
     */
    public function getCustomizeOptions()
    {
        return $this->customizeOptions;
    }

    /**
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * virtual translate message
     *
     * @param string $value value
     *
     * @return string same value
     */
    public function trans($value)
    {
        return $value;
    }

    /**
     * Gets the routeParameters.
     *
     * @return string
     */
    public function getRouteParameters()
    {
        if (!$this->routeParameters) {
            return array();
        }

        return $this->routeParameters;
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
        $this->routeParameters[$name] = $parameter;

        return $this;
    }
}
