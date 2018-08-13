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

class StoreCategoryAdmin extends AbstractAdmin
{

    const ADMIN_ROLE = 'ROLE_ADMIN';

    public $baseRouteName = null;
    protected $translationDomain = 'SonataUserBundle';

     public function getTemplate($name)
    {
        switch ($name) {
            case 'list':
                return 'admin/category/list.html.twig';
                break;
 
            default:
                return parent::getTemplate($name);
                break;
        }
    }
    
    /**
     * Route to action disable
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('disable', $this->getRouterIdParameter() . '/disable');
    }

    /**
     * Form View for User Entity 
     * 
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
                ->add('name', TextType::class, array('label' => 'Api Url'))
                ->add('is_active', null, array('label' => 'Is Active'))
                ->end();
        $formMapper
                ->add('store', ModelListType::class, array(
                    'label' => 'Tienda',
                    'required' => true,
                    'help' => 'Asigne una Tienda'))
                ->end();
    }

    /**
     * Display Filters
     * 
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
//        $user = $this->getUser();
        $datagridMapper
                ->add('name', null, array('label' => 'Store Category Name'))
                ->add('is_active', null, array('label' => 'Is Active'));
        $datagridMapper->add('store', null, array('label' => 'Store'));
//        if (!$this->hasRoleAdmin($user->getRoles())) {
//            $datagridMapper->add('store', null, array('label' => 'Store'), EntityType::class, array(
//                'class' => 'App\Entity\Store',
//                'query_builder' => $this->getStoreFilter()
//                    )
//            );
//        } else {
//            
//        }
    }

    /**
     * Display Show View
     * 
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
                ->add('store', TextType::class, array('label' => 'Store'))
                ->add('name', TextType::class, array('label' => 'Categoy Name'))
                ->add('store_category_id', TextType::class, array('label' => 'Store Category Id'))
                ->add('is_active', null, array('label' => 'Is Active'))
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
                ->add('store_category_id', TextType::class, array('label' => 'Store Category Id'))
                ->add('name', TextType::class, array('label' => 'Name'))
                ->add('is_active', null, array('label' => 'Is Active'))
                ->add('store', TextType::class, array('label' => 'Store'))
                ->add('_action', 'actions', array(
                    'label' => 'Acciones',
                    'actions' => array(
                        'show' => array(),
                        'edit' => array(
                            'template' => 'admin/category/base_edit.html.twig',)
                        )));
    }

    public function createQuery($context = 'list')
    {
        $user = $this->getUser();
        $query = parent::createQuery("list");
        if ($user->getRoles() && !$this->hasRoleAdmin($user->getRoles())) {
            $query->add('select', 'sc')
                    ->add('from', 'App\Entity\StoreCategory sc')
                    ->innerjoin('sc.store', 's')
                    ->where('s.user =:user')
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
        if (is_array($roles) && !empty($roles)) {
            foreach ($roles as $rol) {
                if ($rol->getName() && $rol->getName() === self::ADMIN_ROLE) {
                    return true;
                }
            }
        }
        return false;
    }

    public function getStoreFilter()
    {
        $stores = null;
        $user = $this->getUser();
        if ($user && $user->getId()) {

            $repository = $this->getDoctrine()->getRepository('App:Store');
            $stores = $repository->getStoreQueryByUser($user);
        }
        return $stores;
    }

    public function getDoctrine()
    {
        return $this->getConfigurationPool()->getContainer()->get('doctrine');
    }

}
