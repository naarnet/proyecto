<?php

namespace App\Controller\AdminSonata;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Psr\Log\LoggerInterface;
use App\Entity\StoreCategory;
use App\Entity\StoreMigration;
use App\Entity\MappingCategory;

class StoreMigrationAdminController extends Controller
{

    /**
     * Constant Class
     */
    const ADMIN_ROLE = 'ROLE_ADMIN';

    /**
     *
     * @var Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
     */
    protected $_encoder;

    /**
     *
     * @var Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface
     */
    private $_user;

    /**
     *
     * @var Psr\Log\LoggerInterface 
     */
    protected $_logger;
    protected $_entityManager;

    /**
     * 
     * @param TokenStorageInterface $tokenStorage
     * @param LoggerInterface $logger
     */
    public function __construct(
    TokenStorageInterface $tokenStorage, LoggerInterface $logger
    )
    {
        $this->_user = $tokenStorage->getToken()->getUser();
        $this->_logger = $logger;
    }

    public function mappingCategoriesAction()
    {
        $this->_entityManager = $this->getDoctrine()->getManager();
        $storeMigrationId = $this->getRequest()->get($this->admin->getIdParameter());

        $migration = $this->_entityManager->getRepository(StoreMigration::class)
                ->findOneBy(array('id' => $storeMigrationId));

        $storeCategory = $this->_entityManager->getRepository(StoreCategory::class)
                ->findBy(
                array('store' => $migration->getStore()));

        foreach ($storeCategory as $category) {
            $mappingCategory = $this->_entityManager->getRepository(MappingCategory::class)
                    ->findOneBy(array(
                'store_category' => $category,
                'store_migration' => $migration,
            ));
            if (!$mappingCategory) {
                $mappingCategory = new MappingCategory();
                $mappingCategory->setStoreCategory($category);
                $mappingCategory->setStoreMigration($migration);
                $mappingCategory->setPublished(false);
                try {
                    $this->_entityManager->persist($mappingCategory);
                    $this->_entityManager->flush();
                    $this->_logger->log(100, print_r('Salvado', true));
                } catch (\Exception $ex) {

                    $this->_logger->log(100, print_r($ex->getMessage(), true));
                }
            }
        }
        $this->addFlash('sonata_flash_success', 'Categories imported successfully');
        return new RedirectResponse($this->admin->generateUrl('list'));
    }

//    public function importCategoriesAction()
//    {
//        $storeId = $this->getRequest()->get($this->admin->getIdParameter());
//        
//
//        $data = array(
//            'username' => "magento",
//            'password' => 'magento2'
//        );
//
//        $json = json_encode($data);
//
//        $ch = curl_init('http://m22.qbo.tech:8014/rest/V1/integration/admin/token');
//
//        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
//        curl_setopt($ch, CURLOPT_POST, 1);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
//        curl_setopt($ch, CURLOPT_TIMEOUT, 29);
//
//        if (curl_errno($ch))
//        {
//
//            $response['status_time'] = '501';
//            $response['connect_time'] = 'Time out';
//            $view = $this->view($response, '501');
//            return $this->handleView($view);
//        }
//
//        $json_token = curl_exec($ch);
//        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//        curl_close($ch);
//
//
//        $jsonToken = json_decode($json_token, true);
////        $chProduct = curl_init('http://m22.qbo.tech:8014/rest/V1/products/MT01');
//        $chProduct = curl_init('http://m22.qbo.tech:8014/rest/V1/categories');
//        curl_setopt($chProduct, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $jsonToken . ''));
//
//        curl_setopt($chProduct, CURLOPT_HTTPGET, 1);
//        curl_setopt($chProduct, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($chProduct, CURLOPT_FOLLOWLOCATION, 1);
//        curl_setopt($chProduct, CURLOPT_TIMEOUT, 29);
//
//        if (curl_errno($chProduct))
//        {
//
//            $response['status_time'] = '501';
//            $response['connect_time'] = 'Time out';
//            $view = $this->view($response, '501');
//        }
//
//        $result = curl_exec($chProduct);
//        $status1 = curl_getinfo($chProduct, CURLINFO_HTTP_CODE);
//
//        curl_close($chProduct);
//
//        $categories = json_decode($result, true);
//        $cat_arrays = [];
//
//        if ($categories && is_array($categories) && !empty($categories))
//        {
//            $cat_arrays = $this->getArrayRecursive($categories, []);
//        }
//
//        $this->createCategoryByCollection($cat_arrays, $storeId);
//        
//        $this->addFlash('sonata_flash_success', 'Categories imported successfully');
//        
//        return new RedirectResponse($this->admin->generateUrl('list'));
//    }
//
//    public function createCategoryByCollection($cat_arrays, $storeId)
//    {
//        foreach ($cat_arrays as $category)
//        {
//            if ($this->isStoreCategoryReadyToSave($category))
//            {
//                $entityManager = $this->getDoctrine()->getManager();
//                $storeCategory = $entityManager->getRepository(StoreCategory::class)
//                        ->findOneBy(array('store_category_id' => $category['id']));
//                $this->setStoreToSave($storeCategory, $category, $storeId);
//            }
//        }
//    }
//
//    public function setStoreToSave($storeCategory, $category, $storeId)
//    {
//        $saveStore = true;
//        $entityManager = $this->getDoctrine()->getManager();
//        $store = $entityManager->getRepository(Store::class)->find($storeId);
//
//        if (!is_null($storeCategory) && is_array($category) && !empty($category))
//        {
//            if (
//                    $storeCategory->getStoreCategoryId() === $category['id'] &&
//                    $storeCategory->getName() === $category['name'] &&
//                    $storeCategory->getParentCategoryId() === $category['parent_id'] &&
//                    $storeCategory->getIsActive() === $category['is_active']
//            )
//            {
//                $saveStore = false;
//            }
//        } else
//        {
//            $storeCategory = new StoreCategory();
//        }
//        if ($saveStore && $store)
//        {
//            $storeCategory->setIsActive($category['is_active']);
//            $storeCategory->setName($category['name']);
//            $storeCategory->setParentCategoryId($category['parent_id']);
//            $storeCategory->setStoreCategoryId($category['id']);
//            $storeCategory->setStore($store);
//        }
//        try {
//            $entityManager->persist($storeCategory);
//            $entityManager->flush();
//        } catch (\Exception $ex) {
//            $this->_logger->log(100, print_r($ex->getMessage()));
//        }
//    }
//
//    /**
//     * @param type $category
//     * @return boolean
//     */
//    public function isStoreCategoryReadyToSave($category)
//    {
//        $isReady = false;
//        foreach ($category as $value)
//        {
//            if (isset($value) && !empty($value))
//            {
//                $isReady = true;
//            } else
//            {
//                return false;
//            }
//        }
//        return $isReady;
//    }
//
//    function getArrayRecursive($categories, $categories_arrays)
//    {
//        if (is_array($categories))
//        {
//            $categories_arrays[] = $this->getValuesFromArray($categories);
//        }
//        if (is_array($categories) && isset($categories['children_data']) && !empty($categories['children_data']))
//        {
//            $children = $categories['children_data'];
//            foreach ($children as $cat)
//            {
//                $categories_arrays = $this->getArrayRecursive($cat, $categories_arrays);
//            }
//        }
//        return $categories_arrays;
//    }
//
//    public function getValuesFromArray($array)
//    {
//        $temp = [];
//        if (is_array($array) && !empty($array))
//        {
//            $temp['id'] = isset($array['id']) ? $array['id'] : '';
//            $temp['parent_id'] = isset($array['parent_id']) ? $array['parent_id'] : '';
//            $temp['name'] = isset($array['name']) ? $array['name'] : '';
//            $temp['is_active'] = isset($array['is_active']) ? $array['is_active'] : '';
//            $temp['position'] = isset($array['position']) ? $array['position'] : '';
//            $temp['level'] = isset($array['level']) ? $array['level'] : '';
//            $temp['product_count'] = isset($array['product_count']) ? $array['product_count'] : '';
//        }
//        return $temp;
//    }
//
//    /**
//     * Check if user has admin roles
//     * @param type $roles
//     * @return boolean
//     */
//    public function hasRoleAdmin($roles)
//    {
//        if (is_array($roles) && !empty($roles))
//        {
//            foreach ($roles as $rol)
//            {
//                if ($rol->getName() && $rol->getName() === self::ADMIN_ROLE)
//                {
//                    return true;
//                }
//            }
//        }
//        return false;
//    }
//
//    /**
//     * This method can be overloaded in your custom CRUD controller.
//     * It's called from createAction.
//     *
//     * @param mixed $object
//     *
//     * @return Response|null
//     */
//    protected function preCreate(Request $request, $object)
//    {
//        $this->setUserToStore($object);
//    }
//
//    /**
//     * @param Request $request
//     * @param type $object
//     */
//    protected function preEdit(Request $request, $object)
//    {
//        $this->setUserToStore($object);
//    }
//    
//    /**
//     * Set User to Store
//     * @param type $object
//     */
//    public function setUserToStore($object)
//    {
//        if ($this->_user->getRoles() && !$this->hasRoleAdmin($this->_user->getRoles()))
//        {
//            $object->setUser($this->_user);
//        }
//    }
}
