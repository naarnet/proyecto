<?php

namespace App\Controller\AdminSonata;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Psr\Log\LoggerInterface;
use App\Entity\MarketplaceCategory;
use App\Entity\Marketplace;
use App\Helper\BaseHelper;

class MarketplaceAdminController extends Controller
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
    
    /**
     *
     * @var App\Helper\BaseHelper; 
     */
    protected $_helperData;
    
    protected $_parentId = null;

    /**
     * 
     * @param UserPasswordEncoderInterface $encoder
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        UserPasswordEncoderInterface $encoder,
        TokenStorageInterface $tokenStorage,
        BaseHelper $helperData,    
        LoggerInterface $logger
    ) {
        $this->_encoder = $encoder;
        $this->_user = $tokenStorage->getToken()->getUser();
        $this->_logger = $logger;
        $this->_helperData = $helperData;
    }

    public function importProductAction()
    {
        
    }

    /**
     * Import Categories from MarketPlace
     * @return RedirectResponse
     */
    public function importCategoriesAction()
    {
        $marketplaceId = $this->getRequest()->get($this->admin->getIdParameter());
        $categories = $this->_helperData->getMarketPlaceCategory();
        foreach ($categories as $category)
        {
            $this->createCategoryByCollection($category, $marketplaceId, null);
        }
        $this->addFlash('sonata_flash_success', 'Categories imported successfully');
        return new RedirectResponse($this->admin->generateUrl('list'));
    }

    public function createCategoryByCollection($category, $marketplaceId,$parentCategoryId = null)
    {

        if (isset($category['id']) && !empty($category['id'])) {
            if ($this->isStoreCategoryReadyToSave($category)) {
                $this->setStoreToSave($category, $marketplaceId, $parentCategoryId);
            }
            $childrenData = $this->_helperData->getChildrenMarketPlaceCategory($category['id']);
            $parentCategoryId = $category['id'];
            $this->_logger->log(100, print_r($parentCategoryId, true));
            $this->_logger->log(100, print_r(count($childrenData), true));
            if (isset($childrenData['children_categories']) && !empty($childrenData['children_categories'])) {
                $childrenCategories = $childrenData['children_categories'];
                foreach ($childrenCategories as $children)
                {
                    $this->createCategoryByCollection($children, $marketplaceId, $parentCategoryId);
                }
            }
        }

//        if ($this->isStoreCategoryReadyToSave($category)) {
//            $this->setStoreToSave($category, $marketplaceId, null);
//        }
//        if (isset($category['id']) && !empty($category['id'])) {
//
//            $parentCategoryId = $category['id'];
//            if (isset($childrenData['children_categories']) && !empty($childrenData['children_categories'])) {
//                $childrenCategories = $childrenData['children_categories'];
//                foreach ($childrenCategories as $children)
//                {
//                    $this->setStoreToSave($children, $marketplaceId, $parentCategoryId);
//                }
//            }
//        }
    }

    public function setStoreToSave($category, $storeId, $category_parent_id = null)
    {
        $saveStore = true;
        $entityManager = $this->getDoctrine()->getManager();
        $storeCategory = $entityManager->getRepository(MarketplaceCategory::class)
                ->findOneBy(array('marketplace_category_id' => $category['id']));
        $marketplace = $entityManager->getRepository(Marketplace::class)->find($storeId);
        
        
        if (!is_null($storeCategory) && is_array($category) && !empty($category)) {
            if (
                    $storeCategory->getName() === $category['name'] &&
                    $storeCategory->getMarketplaceCategoryId() === $category['id']
            ) {
                $saveStore = false;
            }
        } else {
            $storeCategory = new MarketplaceCategory();
        }

        if ($saveStore && $marketplace) {
            $storeCategory->setName($category['name']);
            $storeCategory->setMarketplaceCategoryId($category['id']);
            $storeCategory->setMarketplace($marketplace);
            !is_null($category_parent_id) && !is_string($category_parent_id) ? $storeCategory->setParentCategoryId($category_parent_id) : '';

            try {
                $entityManager->persist($storeCategory);
                $entityManager->flush();
                $this->_logger->log(100, print_r('Salvado', true));
            } catch (\Exception $ex) {
                $this->_logger->log(100, print_r('Hera tu puta madre', true));
                $this->_logger->log(100, print_r($ex->getMessage(), true));
            }
        }
          
    }

    /**
     * @param type $category
     * @return boolean
     */
    public function isStoreCategoryReadyToSave($category)
    {
        $isReady = false;
        if (is_array($category) && !empty($category)) {
            foreach ($category as $value)
            {
                if (isset($value) && !empty($value)) {
                    $isReady = true;
                } else {
                    return false;
                }
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
            foreach ($children as $cat)
            {
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
     * Create action.
     *
     * @throws AccessDeniedException If access is not granted
     *
     * @return Response
     */
    public function createAction()
    {

        $request = $this->getRequest();
        // the key used to lookup the template
        $templateKey = 'edit';

        $this->admin->checkAccess('create');

        $class = new \ReflectionClass($this->admin->hasActiveSubClass() ? $this->admin->getActiveSubClass() : $this->admin->getClass());

        if ($class->isAbstract()) {
            return $this->renderWithExtraParams(
                            '@SonataAdmin/CRUD/select_subclass.html.twig', [
                        'base_template' => $this->getBaseTemplate(),
                        'admin' => $this->admin,
                        'action' => 'create',
                            ], null
            );
        }

        $newObject = $this->admin->getNewInstance();

        $preResponse = $this->preCreate($request, $newObject);
        if (null !== $preResponse) {
            return $preResponse;
        }

        $this->admin->setSubject($newObject);

        /** @var $form \Symfony\Component\Form\Form */
        $form = $this->admin->getForm();
        $form->setData($newObject);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $isFormValid = $form->isValid();

            // persist if the form was valid and if in preview mode the preview was approved
            if ($isFormValid && (!$this->isInPreviewMode() || $this->isPreviewApproved())) {
                $submittedObject = $form->getData();

                if ($this->_user->getRoles() && !$this->hasRoleAdmin($this->_user->getRoles())) {
                    $submittedObject->setUser($this->_user);
                }

                $this->admin->setSubject($submittedObject);
                $this->admin->checkAccess('create', $submittedObject);

                try {
                    $newObject = $this->admin->create($submittedObject);

                    if ($this->isXmlHttpRequest()) {
                        return $this->renderJson([
                                    'result' => 'ok',
                                    'objectId' => $this->admin->getNormalizedIdentifier($newObject),
                                        ], 200, []);
                    }

                    $this->addFlash(
                            'sonata_flash_success', $this->trans(
                                    'flash_create_success', ['%name%' => $this->escapeHtml($this->admin->toString($newObject))], 'SonataAdminBundle'
                            )
                    );

                    // redirect to edit mode
                    return $this->redirectTo($newObject);
                } catch (ModelManagerException $e) {
                    $this->handleModelManagerException($e);

                    $isFormValid = false;
                }
            }

            // show an error message if the form failed validation
            if (!$isFormValid) {
                if (!$this->isXmlHttpRequest()) {
                    $this->addFlash(
                            'sonata_flash_error', $this->trans(
                                    'flash_create_error', ['%name%' => $this->escapeHtml($this->admin->toString($newObject))], 'SonataAdminBundle'
                            )
                    );
                }
            } elseif ($this->isPreviewRequested()) {
                // pick the preview template if the form was valid and preview was requested
                $templateKey = 'preview';
                $this->admin->getShow();
            }
        }

        return parent::createAction();
    }

    /**
     * Edit action.
     *
     * @param int|string|null $id
     *
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedException If access is not granted
     *
     * @return Response|RedirectResponse
     */
    public function editAction($id = null)
    {
        $request = $this->getRequest();
        // the key used to lookup the template
        $templateKey = 'edit';

        $id = $request->get($this->admin->getIdParameter());
        $existingObject = $this->admin->getObject($id);

        if (!$existingObject) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }

        $this->checkParentChildAssociation($request, $existingObject);

        $this->admin->checkAccess('edit', $existingObject);

        $preResponse = $this->preEdit($request, $existingObject);
        if (null !== $preResponse) {
            return $preResponse;
        }

        $this->admin->setSubject($existingObject);
        $objectId = $this->admin->getNormalizedIdentifier($existingObject);

        /** @var $form Form */
        $form = $this->admin->getForm();
        $form->setData($existingObject);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $isFormValid = $form->isValid();

            // persist if the form was valid and if in preview mode the preview was approved
            if ($isFormValid && (!$this->isInPreviewMode() || $this->isPreviewApproved())) {
                $submittedObject = $form->getData();

                if ($this->_user->getRoles() && !$this->hasRoleAdmin($this->_user->getRoles())) {
                    $submittedObject->setUser($this->_user);
                }
                $this->admin->setSubject($submittedObject);

                try {
                    $existingObject = $this->admin->update($submittedObject);

                    if ($this->isXmlHttpRequest()) {
                        return $this->renderJson([
                                    'result' => 'ok',
                                    'objectId' => $objectId,
                                    'objectName' => $this->escapeHtml($this->admin->toString($existingObject)),
                                        ], 200, []);
                    }

                    $this->addFlash(
                            'sonata_flash_success', $this->trans(
                                    'flash_edit_success', ['%name%' => $this->escapeHtml($this->admin->toString($existingObject))], 'SonataAdminBundle'
                            )
                    );

                    // redirect to edit mode
                    return $this->redirectTo($existingObject);
                } catch (ModelManagerException $e) {
                    $this->handleModelManagerException($e);

                    $isFormValid = false;
                } catch (LockException $e) {
                    $this->addFlash('sonata_flash_error', $this->trans('flash_lock_error', [
                                '%name%' => $this->escapeHtml($this->admin->toString($existingObject)),
                                '%link_start%' => '<a href="' . $this->admin->generateObjectUrl('edit', $existingObject) . '">',
                                '%link_end%' => '</a>',
                                    ], 'SonataAdminBundle'));
                }
            }

            // show an error message if the form failed validation
            if (!$isFormValid) {
                if (!$this->isXmlHttpRequest()) {
                    $this->addFlash(
                            'sonata_flash_error', $this->trans(
                                    'flash_edit_error', ['%name%' => $this->escapeHtml($this->admin->toString($existingObject))], 'SonataAdminBundle'
                            )
                    );
                }
            } elseif ($this->isPreviewRequested()) {
                // enable the preview template if the form was valid and preview was requested
                $templateKey = 'preview';
                $this->admin->getShow();
            }
        }
        return parent::editAction();
    }

    private function checkParentChildAssociation(Request $request, $object)
    {
        if (!($parentAdmin = $this->admin->getParent())) {
            return;
        }

        // NEXT_MAJOR: remove this check
        if (!$this->admin->getParentAssociationMapping()) {
            return;
        }

        $parentId = $request->get($parentAdmin->getIdParameter());

        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $propertyPath = new PropertyPath($this->admin->getParentAssociationMapping());

        if ($parentAdmin->getObject($parentId) !== $propertyAccessor->getValue($object, $propertyPath)) {
            // NEXT_MAJOR: make this exception
            @trigger_error("Accessing a child that isn't connected to a given parent is deprecated since 3.34"
                            . " and won't be allowed in 4.0.", E_USER_DEPRECATED
            );
        }
    }

}
