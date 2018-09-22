<?php

namespace App\Controller\Api;

use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Psr\Log\LoggerInterface;
use App\Helper\BaseHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use App\Entity\Restaurante;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Delete;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class RestauranteController extends FOSRestController
{

    protected $_logger;
    protected $_baseHelper;
    private $user;
    
    /**
     * 
     * @param LoggerInterface $logger
     * @param BaseHelper $baseHelper
     */
    public function __construct(
        LoggerInterface $logger,
        BaseHelper $baseHelper,
        TokenStorageInterface $tokenStorage
    ) {
        $this->_logger = $logger;
        $this->_baseHelper = $baseHelper;
    }
    
    /**
     * List of restaurant.
     *
     * This call takes into account all confirmed awards, but not pending or refused awards.
     *
     * @Route("/api/restaurantes", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns list of restaurant",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Restaurante::class, groups={"full"}))
     *     )
     * )
     * GET Route annotation
     *  @Get("/restaurantes")
     */
    public function getRestaurantesAction()
    {
        $repository = $this->getDoctrine()->getRepository(Restaurante::class);
        $restaurantes = $repository->findAll();

        $view = $this->view($restaurantes);
        return $this->handleView($view);
    }


    /**
     * Get restaurant.
     *
     * This call takes into account all confirmed awards, but not pending or refused awards.
     *
     * @Route("/api/restaurante/{id}", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns restaurante by id",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Restaurante::class, groups={"full"}))
     *     )
     * )
     * @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     type="string",
     *     description="The field used to order rewards"
     * )
     * GET Route annotation
     *  @Get("/restaurante/{id}")
     */
    public function getRestauranteAction(Request $request, $id)
    {
        if (isset($id) && !is_null($id))
        {
            $repository = $this->getDoctrine()->getRepository(Restaurante::class);
            $restaurante = $repository->find($id);
            if ($restaurante)
            {
                $view = $this->view($restaurante);
                return $this->handleView($view);
            }
        }
        $view = $this->view([]);
        return $this->handleView($view);
    }

    /**
     * Create restaurant.
     *
     * This call takes into account all confirmed awards, but not pending or refused awards.
     *
     * @Route("/api/create-restaurante", methods={"POST"})
     * @SWG\Response(
     *     response=200,
     *     description="Create restaurante by params",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Restaurante::class, groups={"full"}))
     *     )
     * )
     * @SWG\Parameter(
     *     name="nombre",
     *     in="query",
     *     type="string",
     *     description="Name of Restaurant"
     * )
     * @SWG\Parameter(
     *     name="direccion",
     *     in="query",
     *     type="string",
     *     description="Address of Restaurant"
     * )
     * @SWG\Parameter(
     *     name="descripcion",
     *     in="query",
     *     type="string",
     *     description="Description of Restaurant"
     * )
     * @SWG\Parameter(
     *     name="precio",
     *     in="query",
     *     type="string",
     *     description="Price of Restaurant"
     * )
     * GET Route annotation
     *  @Post("/create-restaurante")
     */
    public function postRestauranteAction(Request $request)
    {
        // $request->getContent();
        $data = $request->getContent();
        $response = [];
        $requestData = json_decode($data, true);
        $this->_logger->log(100, print_r('cojone', true));

        if (!empty($requestData) && is_array($requestData) && (isset($requestData['nombre']) && !empty($requestData['nombre'])))
        {
            $entityManager = $this->getDoctrine()->getManager();

            //Create restaurant
            $restaurante = new Restaurante();
            $restaurante->setNombre($requestData['nombre']);
            $restaurante->setDescription($requestData['description']);
            $restaurante->setDireccion($requestData['direccion']);
            $restaurante->setPrecio($requestData['precio']);
            
            try {
                $entityManager->persist($restaurante);
                $entityManager->flush();
                $view = $this->view($restaurante);
            } catch (\Exception $ex) {
                $this->_logger->log(100, print_r($ex->getMessage()));
                $response['message'] = $ex->getMessage();
                $view = $this->view($response, 500);
            }
        } else
        {
            $response['message'] = 'Debe insertar todos los datos para poder crear un restaurante';
            $view = $this->view($response, 400);
        }
        return $this->handleView($view);
    }
    
    /**
     * Edit restaurant.
     *
     * This call takes into account all confirmed awards, but not pending or refused awards.
     *
     * @Route("/api/edit-restaurante", methods={"PUT","POST"})
     * @SWG\Response(
     *     response=200,
     *     description="Create restaurante by params",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Restaurante::class, groups={"full"}))
     *     )
     * )
     * @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     type="string",
     *     description="Id of Restaurant"
     * )
     * @SWG\Parameter(
     *     name="nombre",
     *     in="query",
     *     type="string",
     *     description="Name of Restaurant"
     * )
     * @SWG\Parameter(
     *     name="direccion",
     *     in="query",
     *     type="string",
     *     description="Address of Restaurant"
     * )
     * @SWG\Parameter(
     *     name="descripcion",
     *     in="query",
     *     type="string",
     *     description="Description of Restaurant"
     * )
     * @SWG\Parameter(
     *     name="precio",
     *     in="query",
     *     type="string",
     *     description="Price of Restaurant"
     * )
     * GET Route annotation
     *  @Put("/edit-restaurante")
     */
    public function putRestauranteAction(Request $request)
    {
        // $request->getContent();
        $data = $request->getContent();
        $response = [];
        $this->_logger->log(100, print_r($data, true));
        $requestData = json_decode($data, true);
        $this->_logger->log(100, print_r($request->query->get('id'), true));
        
        if (!empty($requestData) && is_array($requestData) && (isset($requestData['id']) && !empty($requestData['id'])))
        {
            $entityManager = $this->getDoctrine()->getManager();
            $restaurante = $entityManager->getRepository(Restaurante::class)->find($requestData['id']);
            if ($restaurante)
            {
                $restaurante->setNombre($requestData['nombre']);
                $restaurante->setDescription($requestData['description']);
                $restaurante->setDireccion($requestData['direccion']);
                $restaurante->setPrecio($requestData['precio']);
                try {
                    $entityManager->persist($restaurante);
                    $entityManager->flush();
                    $view = $this->view($restaurante);
                } catch (\Exception $ex) {
                    $this->_logger->log(100, print_r($ex->getMessage()));
                    $response['message'] = $ex->getMessage();
                    $view = $this->view($response, 500);
                }
            } else
            {
                $response['message'] = 'El restaurante no se encuentra disponible a editar';
                $view = $this->view($response, 400);
            }
        } else
        {
            $response['message'] = 'Debe insertar todos los datos para poder crear un restaurante';
            $view = $this->view($response, 400);
        }
        return $this->handleView($view);
    }

    /**
     * Delete Restaurante.
     *
     * This call takes into account all confirmed awards, but not pending or refused awards.
     *
     * @Route("/api/delete-restaurante/{id}", methods={"DELETE"})
     * @SWG\Response(
     *     response=200,
     *     description="Delete restaurante by id",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Restaurante::class, groups={"full"}))
     *     )
     * )
     * @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     type="string",
     *     description="The field of restaurant"
     * )
     * GET Route annotation
     *  @Delete("/delete-restaurante/{id}")
     */
    public function deleteRestauranteAction(Request $request, $id)
    {
        if (isset($id) && !is_null($id))
        {
            $entityManager = $this->getDoctrine()->getManager();
            $repository = $entityManager->getRepository(Restaurante::class);
            $restaurante = $repository->find($id);
            if ($restaurante)
            {
                $view = $this->view($restaurante);
                return $this->handleView($view);
            }
        }
        $view = $this->view([]);
        return $this->handleView($view);
    }

}
