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
use Sonata\AdminBundle\Form\Type\ModelListType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

//use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;

class StoreAdmin extends AbstractAdmin
{

    const ADMIN_ROLE = 'ROLE_ADMIN';

    public $baseRouteName = null;
    protected $translationDomain = 'SonataUserBundle';

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

        $user = $this->getUser();
        $formMapper
                ->add('name', TextType::class, array('label' => 'Name'))
                ->end();

        if ($this->hasRoleAdmin($user->getRoles())) {
            $formMapper
                    ->add('user', ModelListType::class, array(
                        'label' => 'Usuario',
                        'required' => true,
                        'help' => 'Asigne su usuario'))
                    ->end();
        }
        $formMapper
                ->add('conexion', ModelListType::class, array(
                    'label' => 'Conexion',
                    'required' => true,
                    'help' => 'Modo de Conexión'))
                ->add('store_entity_role', ModelListType::class, array(
                    'label' => 'Store Role',
                    'required' => true,
                    'help' => 'Store Role'))
                ->end();
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
                ->add('store_entity_role', null, array('label' => 'Store Role'))
                ->add('conexion', null, array('label' => 'Conexion Mode'));

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
                ->add('_action', 'actions', array(
                    'label' => 'Acciones',
                    'actions' => array(
                        'show' => array(),
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
        if (is_array($roles) && !empty($roles)) {
            foreach ($roles as $rol)
            {
                if ($rol->getName() && $rol->getName() === self::ADMIN_ROLE) {
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
        if ($user && $user->getId()) {

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

}
