<?php

namespace DutchBridge\DatatableBundle\Datatable;

use Twig_Environment as Twig;

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
    private $twig;

    /**
     * The Twig template.
     *
     * @var string
     */
    private $template;

    /**
     * The css sDom parameter for:
     *  - dataTables_length,
     *  - dataTables_filter,
     *  - dataTables_info,
     *  - pagination
     *
     * @var array
     */
    private $sDomOptions;

    /**
     * The table id selector.
     *
     * @var string
     */
    private $tableId;

    /**
     * Content for the table header cells.
     *
     * @var array
     */
    private $tableHeaders;

    /**
     * The aoColumns fields.
     *
     * @var array
     */
    private $fields;

    /**
     * The aoColumns fields.
     *
     * @var array
     */
    private $action;

    /**
     * The sAjaxSource path.
     *
     * @var string
     */
    private $sAjaxSource;

    /**
     * Array for custom options.
     *
     * @var array
     */
    private $customizeOptions;

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
}
