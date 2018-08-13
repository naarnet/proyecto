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
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\AdminBundle\Form\Type\ModelType;

class StoreMigrationAdmin extends AbstractAdmin
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
        $collection->add('mappingCategories', $this->getRouterIdParameter() . '/mappingCategories');
    }

    /**
     * Form View for User Entity 
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        

        $formMapper
                ->with('Store', array(
                    'class' => 'col-md-6',
                    'box_class' => 'box box-solid box-success',
                    'description' => 'Selecccione la Tienda',))
                ->add('store', ModelListType::class, array())
                ->end();
        $formMapper
                ->with('Marketplace', array(
                    'class' => 'col-md-6',
                    'box_class' => 'box box-solid box-success',
                    'description' => 'Selecccione el Marketplace a Migrar',))
                ->add('marketplace', ModelListType::class, array('label' => 'Marketplace'
                ))->end();
    }

    /**
     * Display Filters
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
                ->add('store', null, array('label' => 'Store Name'))
                ->add('marketplace', null, array('label' => 'Marketplace'))
        ;
    }

    /**
     * Display Show View
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
                ->add('store', TextType::class, array('label' => 'Store Name'))
                ->add('marketplace', TextType::class, array('label' => 'Marketplace'))
        ;
    }

    /**
     * Display List View
     * 
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
                ->add('store', TextType::class, array('label' => 'Store Name'))
                ->add('marketplace', TextType::class, array('label' => 'Marketplace'))
                ->add('_action', 'actions', array(
                    'label' => 'Acciones',
                    'actions' => array(
                        'show' => array(),
                        'edit' => array(),
                        'delete' => array(),
                        'mapping' => array(
                            'template' => 'admin/mapping/category/mapping.html.twig',)
        )));
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
                    ->add('from', 'App\Entity\StoreMigration s')
                    ->join('s.store', 'r')
                    ->where('r.user =:user')
                    ->setParameter('user', $user);
        }

        return $query;
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

}
