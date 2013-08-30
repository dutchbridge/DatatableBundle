# DatatableBundle

Symfony2 Datatable For Doctrine Entities.

Status: not yet ready, hard-development

# I will update the documentation ASAP


Before installing DatatableBundle you need to have a working installation of Symfony2.

# Screenshot

<div style="text-align:center"><img alt="Screenshot" src=""></div>

# Installation

1. Download DatatableBundle using composer

    Add DatatableBundle in your composer.json:

    ```js
    {
        "require": {
            "dutchbridge/datatablebundle": "dev-master"
        }
    }
    ```

    Tell composer to download the bundle by running the command:

    ``` bash
    $ php composer.phar update
    ```

2. Enable the bundle

    Enable the bundle in the kernel:

    ``` php
    <?php
    // app/AppKernel.php

    public function registerBundles()
    {
        $bundles = array(
            // ...
            new DutchBridge\DatatableBundle\DutchBridgeDatatableBundle()
        );
    }
    ```

3. Bootstrap 2.3 installation

    The DatatableBundle no contains the assets files from Twitter Bootstrap. You can e.g. download a ZIP archive with the files
    from the Bootstrap repository on Github.

    * Copy into the DatatableBundle\Resources\public\css directory the bootstrap css file.
    * Copy into the DatatableBundle\Resources\public\img directory the bootstrap icons files.
    * Copy into the DatatableBundle\Resources\public\js directory the bootstrap js file.

# Example

1. Create your layout.html.twig

    ``` html
    {% extends '::base.html.twig' %}

    {% block title %}UserBundle{% endblock %}

    {% block stylesheets %}

        <link href="{{ asset('bundles/dutchbridgedatatable/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('bundles/dutchbridgedatatable/css/dataTables_bootstrap.css') }}" rel="stylesheet" type="text/css" />

    {% endblock %}

    {% block body%}

        {% block scripts %}
            <script src="{{ asset('bundles/dutchbridgedatatable/js/jquery-2.0.2.min.js') }}" type="text/javascript"></script>
            <script src="{{ asset('bundles/dutchbridgedatatable/js/bootstrap.min.js') }}" type="text/javascript"></script>
            <script src="{{ asset('bundles/dutchbridgedatatable/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
            <script src="{{ asset('bundles/dutchbridgedatatable/js/dataTables_bootstrap.js') }}" type="text/javascript"></script>
        {% endblock %}

        <div class="container">
            {% block content %}
            {% endblock %}
        </div>

    {% endblock %}
    ```

2. Create your Datatable class

    ``` php
    <?php

    namespace DutchBridge\UserBundle\Datatable;

    use DutchBridge\DatatableBundle\Datatable\AbstractDatatableView;
    use DutchBridge\DatatableBundle\Datatable\Field;
    use DutchBridge\DatatableBundle\Datatable\Action;

    /**
     * Class UserDatatable
     *
     * @package DutchBridge\UserBundle\Datatable
     */
    class UserDatatable extends AbstractDatatableView
    {
        /**
         * {@inheritdoc}
         */
        public function build()
        {
            $this->setTableId('user_datatable');
            $this->setSAjaxSource('user_results');

            $this->setTableHeaders(array(
                'Username',
                'Email',
                'Enabled',
                ''
            ));

            $nameField = new Field('username');

            $emailField = new Field('email');

            $enabledField = new Field('enabled');
            $enabledField->setBSearchable('false');
            $enabledField->setSWidth('90');
            $enabledField->setMRender("render_boolean_icons(data, type, full)");

            $idField = new Field('id');
            $idField->setBSearchable('false');
            $idField->setBSortable('false');
            $idField->setMRender("render_action_icons(data, type, full)");
            $idField->setSWidth('92');

            $this->addField($nameField);
            $this->addField($emailField);
            $this->addField($enabledField);
            $this->addField($idField);

            $editAction  = new Action();
            $editAction->setType('route');
            $editAction->setClass('btn btn-sm btn-primary');
            $editAction->setRoute('user_edit');
            $editAction->setIcon('icon-edit');
            $editAction->addRouteParameter('userId', array('field' => 'id'));

            $removeAction  = new Action();
            $removeAction->setType('route');
            $removeAction->setClass('btn btn-sm btn-danger');
            $removeAction->setRoute('user_remove');
            $removeAction->setIcon('icon-remove');
            $removeAction->addRouteParameter('userId', array('field' => 'id'));
            $removeAction->addRouteParameter('lorem', array('value' => 'ipsum'));

            $this->addAction($editAction);
            $this->addAction($removeAction);
        }
    }
    ```

3. Create your index.html.twig

    ``` html
    {% extends 'SgUserBundle::layout.html.twig' %}

    {% block title %}{{ title }}{% endblock %}

    {% block content %}

        <h2>{{ title }}</h2>

        {{ datatable_render(datatable) }}

    {% endblock %}
    ```

4. Add controller actions

    ``` php
    /**
     * Lists all User entities.
     *
     * @Route("/", name="user")
     * @Method("GET")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        /**
         * @var \DutchBridge\DatatableBundle\Factory\DatatableFactory $factory
         */
        $factory = $this->get('sg_datatables.factory');

        /**
         * @var \DutchBridge\DatatableBundle\Datatable\AbstractDatatableView $datatableView
         */
        $datatableView = $factory->getDatatableView('DutchBridge\UserBundle\Datatable\UserDatatable');

        return array(
            'title' => 'Enabled Users',
            'datatable' => $datatableView,
        );
    }

    /**
     * Get all User entities.
     *
     * @Route("/results", name="user_results")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexResultsAction()
    {
        /**
         * @var \DutchBridge\DatatableBundle\Datatable\DatatableData $datatable
         */
        $datatable = $this->get('sg_datatables')->getDatatable('SgUserBundle:User');

        /**
         * @var \Doctrine\ORM\QueryBuilder $qb
         */
        $callbackFunction =

            function($qb)
            {
                $andExpr = $qb->expr()->andX();
                $andExpr->add($qb->expr()->eq('fos_user.enabled', '1'));
                $qb->andWhere($andExpr);
            };

        $datatable->addWhereBuilderCallback($callbackFunction);

        return $datatable->getSearchResults();
    }
    ```

5. Working with Associations

    We extend the user entity to multiple OneToMany associations (Post and Comment).

    ``` php
    <?php

    namespace DutchBridge\UserBundle\Entity;

    use Doctrine\Common\Collections\ArrayCollection;
    use FOS\UserBundle\Model\User as BaseUser;
    use Doctrine\ORM\Mapping as ORM;
    use DutchBridge\AppBundle\Entity\Comment;
    use DutchBridge\AppBundle\Entity\Post;

    /**
     * Class User
     *
     * @ORM\Entity
     * @ORM\Table(name="fos_user")
     *
     * @package DutchBridge\UserBundle\Entity
     */
    class User extends BaseUser
    {
        /**
         * @ORM\Id
         * @ORM\Column(type="integer")
         * @ORM\GeneratedValue(strategy="AUTO")
         */
        protected $id;

        /**
         * @ORM\OneToMany(
         *     targetEntity="DutchBridge\AppBundle\Entity\Post",
         *     mappedBy="createdBy"
         * )
         */
        private $posts;

        /**
         * @ORM\OneToMany(
         *     targetEntity="DutchBridge\AppBundle\Entity\Comment",
         *     mappedBy="createdBy"
         * )
         */
        private $comments;


        /**
         * Ctor.
         */
        public function __construct()
        {
            parent::__construct();

            // your own logic

            $this->posts = new ArrayCollection();
            $this->comments = new ArrayCollection();
        }

        // ...

    }
    ```

    For a comma-separated view of all blog titles we add the following to the UserDatatable class.

    ``` php
    $this->setTableHeaders(array(
        'Username',
        'Email',
        'Enabled',
        'Posts',
        ''
    ));

    $postsField = new Field('posts');
    $postsField->setRenderArray(true);
    $postsField->setRenderArrayFieldName('title');
    $postsField->setSName('posts.title');

    $this->addField($nameField);
    $this->addField($emailField);
    $this->addField($enabledField);
    $this->addField($postsField);
    $this->addField($idField);
    ```

    The result should looks like that:

    <div style="text-align:center"><img alt="Screenshot" src="https://github.com/stwe/DatatableBundle/raw/master/Resources/screenshots/screenshot2.jpg"></div>

