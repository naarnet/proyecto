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
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class MappingCategoryAdmin extends AbstractAdmin
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
        $collection->add('mapping', $this->getRouterIdParameter() . '/mapping');
    }

    public function getTemplate($name)
    {
        switch ($name) {
            case 'list':
                return 'admin/mapping/list.html.twig';
                break;

            default:
                return parent::getTemplate($name);
                break;
        }
    }

    /**
     * Form View for User Entity 
     * 
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
//        $subject = $this->getSubject();
//        if ($subject->isNew())
//        {
//            // The thumbnail field will only be added when the edited item is created
//            $formMapper->add('thumbnail', FileType::class);
//        }
        $formMapper
//                ->add('store_category', TextType::class, array('label' => 'Store Category', 'disabled' => true,))
//                ->add('store_migration', TextType::class, array('label' => 'Store Migration', 'disabled' => true,))
//                ->add('marketplace_category', ModelAutocompleteType::class,  array(
//                    'property' => array('name'),
//                    'minimum_input_length' => 3,
//                    'items_per_page' => 10
//                ))
                ->add('marketplace_category', ModelListType::class, array(
                    'label' => 'Marketplace Category',
                    'required' => true,
                    'help' => 'Seleccione una Categoría del Marketplace'))
//                ->add('marketplace_category', ModelListType::class, array(
//                    'label' => 'Accesoires associés',
//                    'by_reference' => false
//                        ), array(
//                    'edit' => 'list',
//                    'inline' => 'table'
//                        )
//                )
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
                ->add('store_migration', null, array('label' => 'Store Migration'))
                ->add('store_category', null, array('label' => 'Store Category'))
                ->add('marketplace_category', 'doctrine_orm_model_autocomplete', array(
                        ), null, array(
                    'property' => 'name'
                ))
                ->add('published', null, array('label' => 'Published'));
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
                ->add('store_category_id', TextType::class, array(
                    'label' => 'Store Category Id'))
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
                ->add('store_migration', TextType::class, array('label' => 'Store Migration'))
                ->add('store_category', TextType::class, array('label' => 'Store Category'))
                ->add('published', null, array('editable' => true, 'label' => 'Published'))
//                ->add('marketplace_category', null , array(
//                    'type' => 'doctrine_orm_model_autocomplete',
////                    'multiple' => true,
//                    'property' => 'name'
//                ))
//                ->add('marketplace_category', 'doctrine_orm_model_autocomplete', array(
//                    'editable'=>true,
//                        ), null, array(
//                    'property' => 'name'
//                ))
                ->add(
                        'marketplace_category', ModelListType::class, array(
                    'editable' => true,
                    'type' => 'sonata_type_model_autocomplete',
                    'multiple' => true,
                    'property' => 'name'
                ))
//                ->add('marketplace_category', ModelListType::class, array(
//                    'label' => 'Marketplace Category',
//                    'editable'=>true
//                    ))
//                ->add('marketplace_category', ModelListType::class, array(
//                    'label' => 'Marketplace Category',
//                    'by_reference' => false,
//                    'editable'=>true,
//                        ), array(
//                    'edit' => 'inline',
//                    'inline' => 'table'
//                        )
//                )
                ->add('_action', 'actions', array(
                    'label' => 'Marketplace Category',
                    'actions' => array(
//                        'show' => array(),
                        'mapping' => array(
                            'template' => 'admin/mapping/mapping.html.twig',)
        )));
    }

    public function createQuery($context = 'list')
    {
//        $user = $this->getUser();
        $query = parent::createQuery("list");
//        if ($user->getRoles() && !$this->hasRoleAdmin($user->getRoles())) {
//            $query->add('select', 'sc')
//                    ->add('from', 'App\Entity\StoreCredential sc')
//                    ->innerjoin('sc.store', 's')
//                    ->where('s.user =:user')
//                    ->setParameter('user', $user);
//        }

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

    public function getStoreFilter()
    {
        $stores = null;
        $user = $this->getUser();
        if ($user && $user->getId())
        {

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
