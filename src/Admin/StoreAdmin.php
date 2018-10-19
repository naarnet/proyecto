<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Form\Type\ChoiceFieldMaskType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Sonata\CoreBundle\Form\Type\ImmutableArrayType;
use App\Entity\StoreEntityAttribute;
use App\Helper\BaseHelper;

class StoreAdmin extends AbstractAdmin
{
    public $baseRouteName = null;
    protected $translationDomain = 'SonataUserBundle';
    public $supportsPreviewMode = true;
    protected $_logger;

    const ADMIN_ROLE = 'ROLE_ADMIN';
    
    protected $_baseHelper;
    
    public function __construct(
        $code, 
        $class,
        $baseControllerName
    ) {
        parent::__construct($code, $class, $baseControllerName);
    }

    /**
     * Route to action disable
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('importProduct', $this->getRouterIdParameter() . '/importProduct');
        $collection->add('importCategories', $this->getRouterIdParameter() . '/importCategories');
    }

    /**
     * Form View for User Entity 
     * 
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {

        //Add Tab Store Information
        $this->addStoreInformationDataForm($formMapper);
        //Add Tab Store Conexion
        $this->addStoreConexionDataForm($formMapper);
        //Add Store Field to Form
        $this->addStoreUser($formMapper);
        //Add Credential to Store Conexion
        $this->addStoreCredentialToConnect($formMapper);



        //Change commented lines
//                ->add('username', TextType::class)
//                ->add('password', PasswordType::class)
//                ->add('oauth', ImmutableArrayType::class, [
//                    'label' => '',
//                    'required' => false,
//                    'keys' => [
//                        ['content', \App\Form\TestOauthType::class, [
////                                'sonata_help' => 'Set the content'
//                            ]],
////                        ['public', CheckboxType::class, []],
//                        ['oauth', , ['label' => ' ', 'required' => false]],
//                    ]
//                ])
//                ->add('test', ImmutableArrayType::class, [
//                    'label' => ' ',
//                    'required' => false,
//                    'keys' => [
//                        ['content', \App\Form\TestOauthType::class, [
//                                'label' => ' '
////                                'sonata_help' => 'Set the content'
//                            ]],
////                        ['public', CheckboxType::class, []],
////                        ['alo', \App\Form\BasicOauthType::class, ['label' => '']],
//                    ]
//                ])
//                ->add('uri', TextType::class)
//                ->add('oaut', TextType::class)
//                ->add('oauth', CollectionType::class, array(
//                    'entry_type' => \App\Form\BasicOauthType::class,
//                    'prototype' => true,
//                    'allow_add' => $allow_add,
//                        ), array(
//                    'edit' => 'inline',
//                    'inline' => 'table'
//                ))
//                ->add('route', FormType::class, array(
////                    'data_class' => \App\Form\BasicOauthType::class,
////                    'allow_add' => $allow_add,
////                    'acl_value' => true,
////                    'permissions' =>true
////                    'data_class'=> null
//                        ), array(
//                    'edit' => 'inline',
//                    'inline' => 'table'
//                ))
//                ->add('parameters', null)
//                ->add('store_credential', CollectionType::class, array(
//                    'entry_type' => \App\Form\BasicOauthType::class,
//                    'prototype' => true,
//                    'allow_add' => $allow_add,
//                        ), array(
//                    'edit' => 'inline',
//                    'inline' => 'table'
//                ))
//                ->add('test', ImmutableArrayType::class, [
//                    'label' => '',
//                    'keys' => [
//                        ['content', \App\Form\TestOauthType::class, [
////                                'sonata_help' => 'Set the content'
//                            ]],
////                        ['public', CheckboxType::class, []],
//                        ['alo', \App\Form\BasicOauthType::class, []],
//                    ]
//                ])
//                ->add('route', TextType::class)
    }

    /**
     * Display Filters
     * 
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $user = $this->getUser();
        $datagridMapper
                ->add('name', null, array('label' => 'Name'))
                ->add('store_entity_role', null, array('label' => 'Store Role'));

        if (!$this->hasRoleAdmin($user->getRoles()))
        {
            $datagridMapper->add('user', null, array('label' => 'Usuarios'), EntityType::class, array(
                'class' => 'App\Entity\User',
                'query_builder' => $this->getUserFilter()
                    )
            );
        } else
        {
            $datagridMapper->add('user', null, array('label' => 'Usuario'));
        }
    }

    /**
     * Display Show View
     * 
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
                ->add('name', TextType::class, array('label' => 'Name'))
                ->add('user', TextType::class, array('label' => 'Usuario'))
                ->add('store_entity_role', TextType::class, array('label' => 'Store Role'))
                ->add('conexion', TextType::class, array('label' => 'Conexion Mode'));
    }

    /**
     * Display List View
     * 
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
                ->add('name', TextType::class, array('label' => 'Name'))
                ->add('user', TextType::class, array('label' => 'Usuario'))
                ->add('store_entity_role', TextType::class, array('label' => 'Store Role'))
                ->add('conexion', TextType::class, array('label' => 'Conexion Mode'))
                ->add('country', TextType::class, array('label' => 'Country'))
                ->add('currency', TextType::class, array('label' => 'Currency'))
                ->add('_action', 'actions', array(
                    'label' => 'Acciones',
                    'actions' => array(
                        'edit' => array(),
                        'delete' => array(),
                        'categories' => array(
                            'template' => 'admin/store/list_action_import_categories.html.twig',),
                        'products' => array(
                            'template' => 'admin/store/list_action_import_products.html.twig',)
        )));
    }

    /**
     * Get User 
     * @return type
     */
    public function getUser()
    {
        $container = $this->configurationPool->getContainer();
        $user = $container->get('security.token_storage')->getToken()->getUser();
        return $user;
    }

    /**
     * Check if user has admin roles
     * @param type $roles
     * @return boolean
     */
    public function hasRoleAdmin($roles)
    {
        if (is_array($roles) && !empty($roles))
        {
            foreach ($roles as $rol)
            {
                if ($rol->getName() && $rol->getName() === self::ADMIN_ROLE)
                {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Filter User 
     * @return type
     */
    public function getUserFilter()
    {
        $users = null;
        $user = $this->getUser();
        if ($user && $user->getId())
        {

            $repository = $this->getDoctrine()->getRepository('App:User');
            $users = $repository->findUserById($user->getId());
        }
        return $users;
    }

    /**
     * Get Doctrine
     * @return type
     */
    public function getDoctrine()
    {
        return $this->getConfigurationPool()->getContainer()->get('doctrine');
    }

    /**
     * Query to display on list
     * @param type $context
     * @return QUERY
     */
    public function createQuery($context = 'list')
    {
        $user = $this->getUser();
        $query = parent::createQuery("list");
        if ($user->getRoles() && !$this->hasRoleAdmin($user->getRoles()))
        {
            $query->add('select', 's')
                    ->add('from', 'App\Entity\Store s')
                    ->where('s.user =:user')
                    ->setParameter('user', $user);
        }

        return $query;
    }

    // Add Field To Store Form
    /**
     * Add Store Information Data
     * @param type $formMapper
     */
    public function addStoreInformationDataForm(&$formMapper)
    {
        $formMapper
                ->tab('Store Information')
                ->with('Store Data', array(
                    'class' => 'col-md-6',
                    'box_class' => 'box box-solid box-success',
                    'description' => 'Selecccione la Tienda',))
                ->add('name', TextType::class, array('label' => 'Name'))
                ->add('name', TextType::class, array('label' => 'Name'))
                ->add('country', CountryType::class, array(
                    'label' => 'Country',
                    'required' => true,
                    'help' => 'Seleccione el país'))
                ->add('currency', CurrencyType::class, array(
                    'label' => 'Currency',
                    'required' => true,
                    'help' => 'Seleccione la moneda'))
                ->end();
    }

    /**
     * Add Conexion Data form
     * @param type $formMapper
     */
    public function addStoreConexionDataForm(&$formMapper)
    {
        $formMapper
                ->with('Conexion Data', array(
                    'class' => 'col-md-6',
                    'box_class' => 'box box-solid box-success',
                    'description' => 'Selecccione la Tienda',))
                ->add('language', LanguageType::class, array(
                    'label' => 'Language',
                    'required' => true,
                    'help' => 'Seleccione el Idioma'))
                ->add('store_entity_role', ModelListType::class, array(
                    'label' => 'Store Role',
                    'required' => true,
                    'help' => 'Store Role'))
                ->end();
    }

    /**
     * Display Field Select User by Roles
     * @param type $formMapper
     */
    public function addStoreUser(&$formMapper)
    {
        $user = $this->getUser();
        if ($this->hasRoleAdmin($user->getRoles()))
        {
            $formMapper
                    ->add('user', ModelListType::class, array(
                        'label' => 'Usuario',
                        'required' => true,
                        'help' => 'Asigne su usuario'))
                    ->end();
        }
        $formMapper->end();
    }

    /**
     * Add Store Credentials Fields
     * @param type $formMapper
     */
    public function addStoreCredentialToConnect(&$formMapper)
    {
        $formMapper
                ->tab('Conexion')
                ->with('Credentials', array(
                    'class' => 'col-md-6',
                    'required' => true,
                    'box_class' => 'box box-solid box-success',
                    'description' => 'Selecccione la Tienda',))
                ->add('conexion', ModelListType::class, array(
                    'label' => 'Conexion',
                    'required' => true,
                    'help' => 'Modo de Conexión'))
                ->add('url', TextType::class, array(
                    'label' => 'Store Url',
                    'required' => true,
                    'help' => 'Insert Store Url example: http://m22.qbo.tech:8014/ '))
                ->end()
                ->with('Parameters', array(
                    'class' => 'col-md-6',
                    'box_class' => 'box box-solid box-success',
                    'description' => 'Selecccione los Parámetros de Conexión',))
                ->add('credential', ChoiceFieldMaskType::class, [
                    'choices' => [
                        'Basic Oauth' => 'basic_oauth',
                        'route' => 'route',
                    ],
                    'map' => [
                        'route' => ['test'],
                        'basic_oauth' => ['basic_oauth'],
                    ],
                    'placeholder' => 'Select Conexion',
                    'required' => false
                ])
                ->add('basic_oauth', ImmutableArrayType::class, [
                    'label' => ' ', 'required' => false,
                    'keys' => [
                        ['oauth_username', TextType::class, [
                                'label' => 'Oauth User', 'required' => true
                            ]],
                        ['oauth_password', PasswordType::class, ['label' => 'Oauth Password']],
                    ]
                ])
                ->add('basic_oauth', ImmutableArrayType::class, [
                    'label' => ' ', 'required' => false,
                    'keys' => [
                        ['oauth_username', TextType::class, [
                                'label' => 'Oauth User', 'required' => true
                            ]],
                        ['oauth_password', PasswordType::class, ['label' => 'Oauth Password']],
                    ]
                ])
                ->end()
        ;
    }

    /**
     * Event preUpdate
     * @param type $object
     */
    public function preUpdate($object)
    {
        $this->setStoreAttribute($object);
        parent::preUpdate($object);
    }

    /**
     * Event PrePersist Data
     * @param type $object
     */
    public function prePersist($object)
    {
        $this->setStoreAttribute($object);
        parent::prePersist($object);
    }

    /**
     * Set Store To attribute
     * @param type $object
     * @return type
     */
    public function setStoreAttribute($object)
    {
        $uniqid = $this->getRequest()->query->get('uniqid');
        $formData = $this->getRequest()->request->get($uniqid);
        if (!is_null($formData))
        {
            $this->setAttributeData($formData, $object);
        }
        return $object;
    }

    /**
     * Set Attribute Value
     * @param type $formData
     * @param type $object
     */
    public function setAttributeData($formData, $object)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $this->setStoreUrl($formData, $object);

        if ($formData['credential'] === 'basic_oauth')
        {
            foreach ($formData['basic_oauth'] as $index => $value)
            {
                $storeEntityAttribute = $entityManager->getRepository(StoreEntityAttribute::class)
                        ->findOneBy(array('attribute_code' => $index, 'store' => $object->getId()));
                if (!is_null($value) && !empty($value))
                {
                    if (is_null($storeEntityAttribute))
                    {
                        $storeEntityAttribute = new StoreEntityAttribute();
                        $storeEntityAttribute->setAttributeCode($index);
                    }
                    $storeEntityAttribute->setValue($value);
                    $storeEntityAttribute->setStore($object);
                }
                $entityManager->persist($storeEntityAttribute);
                $object->setStoreEntityAttribute($storeEntityAttribute);
                $entityManager->persist($object);
            }
        }
    }

    public function setStoreUrl($formData, &$object)
    {
        if (isset($formData['url']) && !is_null($formData['url']))
        {
            $entityManager = $this->getDoctrine()->getManager();
            $storeEntityAttribute = $entityManager->getRepository(StoreEntityAttribute::class)
                    ->findOneBy(array('attribute_code' => 'url', 'store' => $object->getId()));

            if (!$storeEntityAttribute)
            {
                $storeEntityAttribute = new StoreEntityAttribute();
                $storeEntityAttribute->setAttributeCode('url');
                $storeEntityAttribute->setValue($formData['url']);
                $storeEntityAttribute->setStore($object);
                $entityManager->persist($storeEntityAttribute);
                $object->setStoreEntityAttribute($storeEntityAttribute);
                $entityManager->persist($object);
            } else
            {
                $object->setUrl($formData['url']);
            }
        }
    }

}
