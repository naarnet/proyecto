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
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Form\Type\CollectionType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class StoreAdmin extends AbstractAdmin
{

    public $baseRouteName = null;
    protected $translationDomain = 'SonataUserBundle';
    public $supportsPreviewMode = true;
    protected $_logger;

    const ADMIN_ROLE = 'ROLE_ADMIN';

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
                ->tab('Store')
                ->with('Store Name', array(
                    'class' => 'col-md-12',
                    'box_class' => 'box box-solid box-success',
                    'description' => 'Selecccione la Nombre de la Tienda',))
                ->add('name', TextType::class, array('label' => 'Name'))
                ->end();
        $formMapper
                ->with('Store Data', array(
                    'class' => 'col-md-6',
                    'box_class' => 'box box-solid box-success',
                    'description' => 'Selecccione la Tienda',))
                ->add('name', TextType::class, array('label' => 'Name'))
                ->add('country', CountryType::class, array(
                    'label' => 'Country',
                    'required' => true,
                    'help' => 'Seleccione el país'))
                ->add('currency', CurrencyType::class, array(
                    'label' => 'Currency',
                    'required' => true,
                    'help' => 'Seleccione la moneda'))
                ->add('language', LanguageType::class, array(
                    'label' => 'Language',
                    'required' => true,
                    'help' => 'Seleccione el Idioma'))
                ->end();
        $formMapper
                ->with('Conexion Data', array(
                    'class' => 'col-md-6',
                    'box_class' => 'box box-solid box-success',
                    'description' => 'Selecccione la Tienda',))
                ->add('conexion', ModelListType::class, array(
                    'label' => 'Conexion',
                    'required' => true,
                    'help' => 'Modo de Conexión'))
                ->add('store_entity_role', ModelListType::class, array(
                    'label' => 'Store Role',
                    'required' => true,
                    'help' => 'Store Role'))
                ->end();
//        $formMapper
//                
//                ->with('admin.conexion', array(
//                    'class' => 'col-md-6',
//                    'box_class' => 'box box-solid box-success',
//                    'description' => 'Selecccione la Tienda',))
//                ->add('conexion', ModelListType::class, array(
//                    'label' => 'Conexion',
//                    'required' => true,
//                    'help' => 'Modo de Conexión'))
//                ->add('store_entity_role', ModelListType::class, array(
//                    'label' => 'Store Role',
//                    'required' => true,
//                    'help' => 'Store Role'))
//                ->end();

        if ($this->hasRoleAdmin($user->getRoles())) {
            $formMapper
                    ->add('user', ModelListType::class, array(
                        'label' => 'Usuario',
                        'required' => true,
                        'help' => 'Asigne su usuario'))
                    ->end();
        }
//        $formMapper->end();
//        $formMapper
//                ->with('Meta data')
////                ->add('store_credential', EntityType::class, [
////                    'class' => \App\Entity\StoreCredential::class,
////                ])
////                ->getAdmin(App\Admin\StoreCredentialAdmin::class)
////                ->attachAdminClass('store_credential')
//                ->add('store_credential', \App\Form\StoreCredentialType::class, [
//                    'sonata_admin' => 'admin.store_credential',
//                    'by_reference' => true,
//                    'data_class' => null,
////                    'btn_list'=>true,
////                    'entry_type' => \App\Form\StoreCredentialType::class
//                        ], array())
//                ->end()
//                ->addChild(
//                'Store Credential', array('uri' => $admin->generateUrl('admin.store|admin.store_credential.list', array('id' => $id)))
//        )
        ;
    }

    public function configureTabMenu(\Knp\Menu\ItemInterface $menu, $action, \Sonata\AdminBundle\Admin\AdminInterface $childAdmin = null)
    {
        if (!$childAdmin && !in_array($action, ['edit', 'show'])) {
            return;
        }

        $admin = $this->isChild() ? $this->getParent() : $this;
        $id = $admin->getRequest()->get('id');

//        $menu->addChild('View Playlist', [
//            'uri' => $admin->generateUrl('show', ['id' => $id])
//        ]);
//        if ($this->isGranted('EDIT')) {
//            $menu->addChild('Edit Playlist', [
//                'uri' => $admin->generateUrl('edit', ['id' => $id])
//            ]);
//        }

        if ($this->isGranted('LIST')) {
            $menu->addChild('ADD CREDENTIAL', ['attributes' => ['icon' => 'fa fa-terminal'],
                'uri' => $admin->generateUrl('admin.store_credential.create', ['id' => $id])
            ]);
        }
//        parent::configureTabMenu($menu, $action, $childAdmin);
    }

//    public function configureOptions(OptionsResolver $resolver)
//    {
//        $resolver->setDefaults(array(
//            'data_class' => \App\Form\StoreCredentialType::class,
//        ));
//    }
//    protected function configureTabMenu(\Knp\Menu\ItemInterface $menu, $action, \Sonata\AdminBundle\Admin\AdminInterface $childAdmin = null)
//    {
//        if (!$childAdmin && !in_array($action, array('edit'))) {
//            return;
//        }
//
//        $admin = $this->isChild() ? $this->getParent() : $this;
//
//        $id = $admin->getRequest()->get('id');
//        $menu->addChild(
//                'Store Credential', array('uri' => $admin->generateUrl('admin.store|admin.store_credential.list', array('id' => $id)))
//        );
//        parent::configureTabMenu($menu, $action, $childAdmin);
//    }

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
//                ->add('conexion', null, array('label' => 'Conexion Mode'));

        if (!$this->hasRoleAdmin($user->getRoles())) {
            $datagridMapper->add('user', null, array('label' => 'Usuarios'), EntityType::class, array(
                'class' => 'App\Entity\User',
                'query_builder' => $this->getUserFilter()
                    )
            );
        } else {
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
        if (is_array($roles) && !empty($roles)) {
            foreach ($roles as $rol) {
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
        if ($user->getRoles() && !$this->hasRoleAdmin($user->getRoles())) {
            $query->add('select', 's')
                    ->add('from', 'App\Entity\Store s')
                    ->where('s.user =:user')
                    ->setParameter('user', $user);
        }

        return $query;
    }

}
