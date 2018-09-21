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

class RestauranteAdmin extends AbstractAdmin
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
     * Form View for User Entity 
     * 
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
                ->add('nombre', TextType::class, array('label' => 'Nombre'))
                ->add('direccion', TextType::class, array('label' => 'Dirección'))
                ->add('description', TextType::class, array('label' => 'Descripción'))
                ->add('precio', TextType::class, array('label' => 'Precio'))
                ->add('imagen', TextType::class, array('label' => 'Imagen'))
                ->end();
    }

    /**
     * Display Filters
     * 
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
                ->add('nombre', null, array('label' => 'Nombre'))
                ->add('direccion', null, array('label' => 'Dirección'))
                ->add('description', null, array('label' => 'Descripción'))
                ->add('precio', null, array('label' => 'Precio'))
                ->add('imagen', null, array('label' => 'Imagen'))
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
                ->add('nombre', TextType::class, array('label' => 'Nombre'))
                ->add('direccion', TextType::class, array('label' => 'Dirección'))
                ->add('description', TextType::class, array('label' => 'Descripción'))
                ->add('precio', TextType::class, array('label' => 'Precio'))
                ->add('imagen', TextType::class, array('label' => 'Imagen'))
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
                ->add('nombre', TextType::class, array('label' => 'Nombre'))
                ->add('direccion', TextType::class, array('label' => 'Dirección'))
                ->add('description', TextType::class, array('label' => 'Descripción'))
                ->add('precio', TextType::class, array('label' => 'Precio'))
                ->add('imagen', TextType::class, array('label' => 'Imagen'))
                ->add('_action', 'actions', array(
                    'label' => 'Acciones',
                    'actions' => array(
                        'show' => array(),
                        'edit' => array(),
                        'delete' => array(),)));
    }

}
