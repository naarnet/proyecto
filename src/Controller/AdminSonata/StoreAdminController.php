<?php

namespace App\Controller\AdminSonata;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Psr\Log\LoggerInterface;
use App\Entity\StoreCategory;
use App\Entity\Store;
use App\Entity\StoreEntityAttribute;
use App\Helper\BaseHelper;
use App\Model\Conexion\BasicOauthConnection;


class StoreAdminController extends Controller
{

    const REQUEST_UNIQUID = 'uniqid';
    /**
     * Constant Class
     */
    const ADMIN_ROLE = 'ROLE_ADMIN';
    const SONATA_FLASH_ERROR = 'sonata_flash_error';
    const SONATA_FLASH_SUCCESS = 'sonata_flash_success';

    /**
     * CONSTANT ERROR STORE CONNECTION
     */
    const ERROR_STORE_CONNECTION = 'Connection Error With the Store .Verify Your authentication Credentials';
    const ERROR_TIME_OUT = 'Time out. ';
    const ERROR_IMPORT_CATEGORY_TIME_OUT = 'Connect time out to import categories';
    const ERROR_IMPORT_CATEGORY = 'Error Connection To Import Categories from Store';
    const ERROR_CREDENTIALS_CONNECTION = 'Error when connecting, Verify credentials and try again. ';
    const IMPORTED_CATEGORY_SUCCESS = 'Categories imported successfully';


    /**
     * CONSTANT ACTIONS 
     */
    const ACTION_LIST = 'list';
    const ACTION_CREATE = 'create';
    const ACTION_EDIT = 'edit';

    /**
     * @var Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
     */
    protected $_encoder;

    /**
     * @var Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface
     */
    private $_user;

    /**
     * @var Psr\Log\LoggerInterface 
     */
    protected $_logger;

    /**
     * @var App\Helper\BaseHelper; 
     */
    protected $_helperData;

    /**
     *
     * @var App\Model\Conexion\BasicOauthConnection
     */
    protected $_basicOauthConnection;

    /**
     * 
     * @param TokenStorageInterface $tokenStorage
     * @param LoggerInterface $logger
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        LoggerInterface $logger,
        BaseHelper $baseHelper,
        BasicOauthConnection $basicOauthConnection
    ) {
        $this->_user = $tokenStorage->getToken()->getUser();
        $this->_logger = $logger;
        $this->_helperData = $baseHelper;
        $this->_basicOauthConnection = $basicOauthConnection;
    }

    public function importProductAction()
    {

    }

    /**
     * Request To get Categories by stores
     * @return RedirectResponse
     */
    public function importCategoriesAction()
    {
        $storeId = $this->getRequest()->get($this->admin->getIdParameter());
        $entityManager = $this->getDoctrine()->getManager();
        $store = $entityManager->getRepository(Store::class)->find($storeId);
        
        //Get store credential data to connect
        $data = $this->_helperData->getStoreCredentialData($store);
        $urlToken = $this->_helperData->getStoreUrlToAccessToken($store->getUrl());
        $ch = $this->_basicOauthConnection->getStoreConnection($data, $urlToken);
        
        //Check if request connection is not false
        if (!$ch) {
            $this->addFlash(self::SONATA_FLASH_ERROR, self::ERROR_TIME_OUT);
            return new RedirectResponse($this->admin->generateUrl(self::ACTION_LIST));
        }
        
        //Get Token from request 
        $json_token = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status !== 200) {
            $this->addFlash(self::SONATA_FLASH_ERROR, self::ERROR_STORE_CONNECTION);
            return new RedirectResponse($this->admin->generateUrl(self::ACTION_LIST));
        } else {
            //Get Token from request 
            $jsonToken = json_decode($json_token, true);
            $chCategories = $this->_basicOauthConnection->getStoreCategoryConnection($store->getUrl(), $jsonToken);
            $this->prepareResultCategories($chCategories, $storeId);

        }

        return new RedirectResponse($this->admin->generateUrl(self::ACTION_LIST));
    }

    /**
     * Prepare result response to save categories
     * @param type $chCategories
     * @param type $storeId
     * @return RedirectResponse
     */
    public function prepareResultCategories($chCategories, $storeId)
    {
        if (!$chCategories) {
            $this->addFlash(self::SONATA_FLASH_ERROR, self::ERROR_IMPORT_CATEGORY_TIME_OUT);
            return new RedirectResponse($this->admin->generateUrl(self::ACTION_LIST));
        }

        //Get Store categories from request
        $result = curl_exec($chCategories);
        $statusCategory = curl_getinfo($chCategories, CURLINFO_HTTP_CODE);

        if ($statusCategory !== 200) {
            $this->addFlash(self::SONATA_FLASH_ERROR, self::ERROR_IMPORT_CATEGORY);
            return new RedirectResponse($this->admin->generateUrl(self::ACTION_LIST));
        } else {

            curl_close($chCategories);

            $categories = json_decode($result, true);
            $cat_arrays = $this->getCategories($categories);
            $this->createCategoryByCollection($cat_arrays, $storeId);
            $this->addFlash(self::SONATA_FLASH_SUCCESS, self::IMPORTED_CATEGORY_SUCCESS);
        }
    }

    /**
     * Return store categories as array 
     * @param type $categories
     * @return array
     */
    public function getCategories($categories)
    {
        $cat_arrays = [];
        if ($categories && is_array($categories) && !empty($categories)) {
            $cat_arrays = $this->getArrayRecursive($categories, []);
        }
        return $cat_arrays;
    }

    /**
     * Create Category Collection to save by StoreId
     * @param type $cat_arrays
     * @param type $storeId
     */
    public function createCategoryByCollection($cat_arrays, $storeId)
    {
        foreach ($cat_arrays as $category) {
            if ($this->isStoreCategoryReadyToSave($category)) {
                $entityManager = $this->getDoctrine()->getManager();
                $storeCategory = $entityManager->getRepository(StoreCategory::class)
                    ->findOneBy(
                        array('store_category_id' => $category['id'], 'store' => $storeId)
                    );
                $this->setStoreToSave($storeCategory, $category, $storeId);
            }
        }
    }

    /**
     * Prepare to save Store and Category
     * @param StoreCategory $storeCategory
     * @param type $category
     * @param type $storeId
     */
    public function setStoreToSave($storeCategory, $category, $storeId)
    {
        $saveStore = true;
        $entityManager = $this->getDoctrine()->getManager();
        $store = $entityManager->getRepository(Store::class)->find($storeId);

        if (!is_null($storeCategory) && is_array($category) && !empty($category)) {
            if ($storeCategory->getStoreCategoryId() === $category['id'] &&
                $storeCategory->getName() === $category['name'] &&
                $storeCategory->getParentCategoryId() === $category['parent_id'] &&
                $storeCategory->getIsActive() === $category['is_active']) {
                $saveStore = false;
            }
        } else {
            $storeCategory = new StoreCategory();
        }
        $this->saveStoreCategory($store, $entityManager, $storeCategory, $category, $saveStore);
    }

    /**
     * Save Store Category
     * @param type $store
     * @param type $entityManager
     * @param type $storeCategory
     * @param type $category
     * @param type $saveStore
     */
    public function saveStoreCategory($store, $entityManager, $storeCategory, $category, $saveStore)
    {
        if ($saveStore && $store) {
            $storeCategory->setIsActive($category['is_active']);
            $storeCategory->setName($category['name']);
            $storeCategory->setParentCategoryId($category['parent_id']);
            $storeCategory->setStoreCategoryId($category['id']);
            $storeCategory->setStore($store);
        }
        try {
            $entityManager->persist($storeCategory);
            $entityManager->flush();
        } catch (\Exception $ex) {
            $this->_logger->log(100, print_r($ex->getMessage()));
        }
    }

    /**
     * @param type $category
     * @return boolean
     */
    public function isStoreCategoryReadyToSave($category)
    {
        $isReady = false;
        foreach ($category as $value) {
            if (isset($value) && !empty($value)) {
                $isReady = true;
            } else {
                return false;
            }
        }
        return $isReady;
    }

    function getArrayRecursive($categories, $categories_arrays)
    {
        if (is_array($categories)) {
            $categories_arrays[] = $this->getValuesFromArray($categories);
        }
        if (is_array($categories) && isset($categories['children_data']) && !empty($categories['children_data'])) {
            $children = $categories['children_data'];
            foreach ($children as $cat) {
                $categories_arrays = $this->getArrayRecursive($cat, $categories_arrays);
            }
        }
        return $categories_arrays;
    }

    public function getValuesFromArray($array)
    {
        $temp = [];
        if (is_array($array) && !empty($array)) {
            $temp['id'] = isset($array['id']) ? $array['id'] : '';
            $temp['parent_id'] = isset($array['parent_id']) ? $array['parent_id'] : '';
            $temp['name'] = isset($array['name']) ? $array['name'] : '';
            $temp['is_active'] = isset($array['is_active']) ? $array['is_active'] : '';
            $temp['position'] = isset($array['position']) ? $array['position'] : '';
            $temp['level'] = isset($array['level']) ? $array['level'] : '';
            $temp['product_count'] = isset($array['product_count']) ? $array['product_count'] : '';
        }
        return $temp;
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
     * This method can be overloaded in your custom CRUD controller.
     * It's called from createAction.
     *
     * @param mixed $object
     *
     * @return Response|null
     */
    protected function preCreate(Request $request, $object)
    {
        $uniqid = $request->query->get(self::REQUEST_UNIQUID);
        $formData = $this->getRequest()->request->get($uniqid);
        $params = [];
        if ($this->admin->hasActiveSubClass()) {
            $params['subclass'] = $request->get('subclass');
        }
        if (!empty($formData)) {
            $canConnect = $this->_basicOauthConnection->getConnectionStatus($formData);
            if (!$canConnect) {
                $this->addFlash(self::SONATA_FLASH_ERROR, self::ERROR_CREDENTIALS_CONNECTION);
                return new RedirectResponse($this->admin->generateUrl(self::ACTION_CREATE, $params));
            }
        }
        $this->setUserToStore($object);
    }

    /**
     * @param Request $request
     * @param type $object
     */
    protected function preEdit(Request $request, $object)
    {
        $uniqid = $request->query->get(self::REQUEST_UNIQUID);
        $formData = $this->getRequest()->request->get($uniqid);
        $data = $this->_basicOauthConnection->getFormCredentialData($formData);
        $hasCredentialToConnect = $this->_basicOauthConnection->hasCredentialsData($data);
        if ($hasCredentialToConnect) {
            $canConnect = $this->_basicOauthConnection->getConnectionStatus($formData);
            if (!$canConnect) {
                $this->addFlash(self::SONATA_FLASH_ERROR, self::ERROR_CREDENTIALS_CONNECTION);
                return new RedirectResponse($this->admin->generateObjectUrl(self::ACTION_EDIT, $object));
            }
        }
        $this->setUserToStore($object);
    }

    /**
     * Set User to Store
     * @param type $object
     */
    public function setUserToStore($object)
    {
        if ($this->_user->getRoles() && !$this->hasRoleAdmin($this->_user->getRoles())) {
            $object->setUser($this->_user);
        }
    }

}


//


//        $json = json_encode($data);
//        $ch = curl_init('http://m22.qbo.tech:8014/rest/V1/integration/admin/token');
//
//        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
//        curl_setopt($ch, CURLOPT_POST, 1);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
//        curl_setopt($ch, CURLOPT_TIMEOUT, 29);
//        if (curl_errno($ch))
//        {
//            $response['status_time'] = '501';
//            $response['connect_time'] = 'Time out';
//            $this->addFlash('sonata_flash_error', 'Time out. ');
//            return new RedirectResponse($this->admin->generateUrl('list'));
//        }
//
//        $json_token = curl_exec($ch);
//        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//        curl_close($ch);
//        if ($status !== 200)
//        {
//            $this->addFlash('sonata_flash_error', 'Error Conexion To Import Categories from Store');
//            return new RedirectResponse($this->admin->generateUrl('list'));
//        } else
//        {
//
//            $jsonToken = json_decode($json_token, true);
////        $chProduct = curl_init('http://m22.qbo.tech:8014/rest/V1/products/MT01');
////        $chProduct = curl_init('http://m22.qbo.tech:8014/rest/V1/categories');
//
//
//            $chProduct = curl_init($store->getUrl() . '/rest/V1/categories');
//            curl_setopt($chProduct, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $jsonToken . ''));
//
//            curl_setopt($chProduct, CURLOPT_HTTPGET, 1);
//            curl_setopt($chProduct, CURLOPT_RETURNTRANSFER, true);
//            curl_setopt($chProduct, CURLOPT_FOLLOWLOCATION, 1);
//            curl_setopt($chProduct, CURLOPT_TIMEOUT, 29);
//
//            if (curl_errno($chProduct))
//            {
//
//                $response['status_time'] = '501';
//                $response['connect_time'] = 'Time out';
//                $view = $this->view($response, '501');
//            }
//
//            $result = curl_exec($chProduct);
//            $status1 = curl_getinfo($chProduct, CURLINFO_HTTP_CODE);
//
//            curl_close($chProduct);
//
//            $categories = json_decode($result, true);
//            $cat_arrays = [];
//
//            if ($categories && is_array($categories) && !empty($categories))
//            {
//                $cat_arrays = $this->getArrayRecursive($categories, []);
//            }
//
//            $this->createCategoryByCollection($cat_arrays, $storeId);
//            $this->addFlash('sonata_flash_success', 'Categories imported successfully');
//        }
//
//        return new RedirectResponse($this->admin->generateUrl('list'));