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

class MarketplaceCategoryAdmin extends AbstractAdmin
{

    public $baseRouteName = null;
    protected $translationDomain = 'SonataUserBundle';

    /**
     * Route to action disable
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('disable', $this->getRouterIdParameter() . '/disable');
    }

    /**
     * Display Filters
     * 
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
                ->add('name', null, array('label' => 'Name'))
                ->add('marketplace_category_id', null, array('label' => 'Marketplace Category Id'))
                ->add('parent_category_id', null, array('label' => 'Parent Category Id'))
                ->add('parent_category_name', null, array('label' => 'Parent Category Name'))
        ;
    }

    /**
     * Display Show View
     * 
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
                ->add('name', TextType::class, array('label' => 'Name'));
    }

    /**
     * Display List View
     * 
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
                ->add('id', TextType::class, array('label' => 'Id'))
                ->add('name', TextType::class, array('label' => 'Name'))
                ->add('marketplace_category_id', TextType::class, array('label' => 'Marketplace Category id'))
                ->add('parent_category_id', TextType::class, array('label' => 'Parent Category id'))
                ->add('parent_category_name', TextType::class, array('label' => 'Parent Category Name'))
                ->add('marketplace', TextType::class, array('label' => 'Marketplace'));
//                ->add('_action', 'actions', array(
//                    'label' => 'Acciones',
//                    'actions' => array(
//                        'show' => array(),
//                        'edit' => array(),
//                        'delete' => array(),
//                        )));
    }

}
