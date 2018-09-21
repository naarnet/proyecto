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
     * Get rest.
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
    public function getRestauranteAction(Request $request,$id)
    {
        $view = View::create();
        if(isset($id) && !is_null($id)){
            $repository = $this->getDoctrine()->getRepository(Restaurante::class);
            $restaurante = $repository->find($id);
            if($restaurante){
                $view = $this->view($restaurante);
                return $this->handleView($view);
            }
        }
        $view = $this->view([]);
        return $this->handleView($view);
    }

    /**
     * List the rewards of the specified user.
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
     *     name="id",
     *     in="query",
     *     type="string",
     *     description="The field used to order rewards"
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
        $this->_logger->log(100, print_r($requestData, true));

        if (!empty($requestData) && is_array($requestData) && (isset($requestData['nombre']) && !empty($requestData['nombre'])))
        {
            $entityManager = $this->getDoctrine()->getManager();
            $restaurante = new Restaurante();
            $restaurante->setNombre($requestData['nombre']);
            $restaurante->setDescription($requestData['descripcion']);
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

}
