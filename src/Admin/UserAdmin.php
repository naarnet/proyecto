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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sonata\AdminBundle\Show\ShowMapper;
//use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sonata\AdminBundle\Datagrid\ORM\ProxyQuery;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
//use Symfony\Component\Form\Extension\Core\Type\;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class UserAdmin extends AbstractAdmin
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
                ->with('Datos Personales', array(
                    'class' => 'col-md-6',
                    'box_class' => 'box box-solid box-danger',
                    'description' => 'La contraseña debe ser de 6 dígitos',))
                ->add('name', TextType::class, array('label' => 'Name'))
                ->add('lastname', TextType::class, array('label' => 'Apellidos'))
                ->add('email', EmailType::class, array('label' => 'Correo Electrónico'))
                ->end();
        $formMapper
                ->with('Credenciales', array(
                    'class' => 'col-md-6',
                    'box_class' => 'box box-solid box-danger',
                    'description' => 'La contraseña debe ser de 6 dígitos',))
                ->add('password', RepeatedType::class, array(
                    'type' => PasswordType::class,
                    'required' => true,
                    'first_options' => array('label' => 'Contraseña'),
                    'second_options' => array('label' => 'Confirmación de Contraseña'),))
                ->add('user_roles', null, array(
                    'label' => 'Rol',
                    'required' => true,
                    'help' => 'Por favor asigne un Rol'))
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
                ->add('name', null, array('label' => 'Name'))
                ->add('lastName', null, array('label' => 'Apellidos'))
                ->add('email', null, array('label' => 'Correo Electrónico'))
                ->add('user_roles', null, array('label' => 'Rol'), EntityType::class , array(
                    'class' => 'App\Entity\Role'
        ));
    }

    /**
     * Display Show View
     * 
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
                ->add('image', 'string', array(
                    'template' => 'AdminBundle:Default:show_image.html.twig'))
                ->add('name', null, array('label' => 'Nombre'))
                ->add('lastName', null, array('label' => 'Apellidos'))
                ->add('email', null, array('label' => 'Correo Electrónico'))
                ->add('user_roles', null, array('label' => 'Rol'));
    }

    /**
     * Display List View
     * 
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
                ->add('name', TextType::class, array('label' => 'Nombre'))
                ->add('lastname', TextType::class, array('label' => 'Apellidos'))
                ->add('email', EmailType::class, array('label' => 'Correo Electrónico'))
                ->add('user_roles', null, array('label' => 'Rol'))
                ->add('_action', 'actions', array(
                    'label' => 'Acciones',
                    'actions' => array(
                        'show' => array(),
                        'edit' => array(),
//                        'disable' => array(
//                            'template' => 'AdminBundle:CRUD:list__action_disable.html.twig'),
                        'delete' => array(),)));
    }

}
