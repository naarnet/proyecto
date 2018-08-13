<?php

namespace App\Controller;

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
use FOS\RestBundle\Controller\Annotations\Get;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ApiController extends FOSRestController
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
        $this->user = $tokenStorage->getToken()->getUser();
    }

    /**
     * List the rewards of the specified user.
     *
     * This call takes into account all confirmed awards, but not pending or refused awards.
     *
     * @Route("/api/user", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns the rewards of an user",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=User::class, groups={"full"}))
     *     )
     * )
     * @SWG\Parameter(
     *     name="order",
     *     in="query",
     *     type="string",
     *     description="The field used to order rewards"
     * )
     * GET Route annotation
     *  @Get("/user")
     */
    public function getUserAction()
    {
        

//        $user = $this->get('security.context')->getToken()->getUser();
//        $this->_logger->log(100,print_r($this->user->getName(),true));
//        $repository = $this->getDoctrine()->getRepository(User::class);
//        $response = array("Volvo1", "BMW", "Toyota");
//        $user = $repository->findAll();

        $data = array(
            'username' => "magento",
            'password' => 'magento2'
        );

        $json = json_encode($data);

        $ch = curl_init('http://m22.qbo.tech:8014/rest/V1/integration/admin/token');

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 29);

        if (curl_errno($ch)) {

            $response['status_time'] = '501';
            $response['connect_time'] = 'Time out';
            $view = $this->view($response, '501');
            return $this->handleView($view);
        }

        $result = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        $jsonToken = json_decode($result, true);
//        $chProduct = curl_init('http://m22.qbo.tech:8014/rest/V1/products/MT01');
        $chProduct = curl_init('http://m22.qbo.tech:8014/rest/V1/categories');
        curl_setopt($chProduct, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $jsonToken . ''));

        curl_setopt($chProduct, CURLOPT_HTTPGET, 1);
        curl_setopt($chProduct, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chProduct, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($chProduct, CURLOPT_TIMEOUT, 29);

        if (curl_errno($chProduct)) {

            $response['status_time'] = '501';
            $response['connect_time'] = 'Time out';
            $view = $this->view($response, '501');
            return $this->handleView($view);
        }

        $result1 = curl_exec($chProduct);
        $status1 = curl_getinfo($chProduct, CURLINFO_HTTP_CODE);

        curl_close($chProduct);

        $jsonResponse = json_decode($result1, true);
        $view = $this->view($jsonResponse, $status1);
        return $this->handleView($view);
    }
    
    /**
     * List the rewards of the specified user.
     *
     * This call takes into account all confirmed awards, but not pending or refused awards.
     *
     * @Route("/api/authentication", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns the rewards of an user",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=User::class, groups={"full"}))
     *     )
     * )
     * @SWG\Parameter(
     *     name="order",
     *     in="query",
     *     type="string",
     *     description="The field used to order rewards"
     * )
     * GET Route annotation
     *  @Get("/authentication")
     */
    public function getMercadoPagoAction()
    {
        $this->_baseHelper->getVimArray();
//        $repository = $this->getDoctrine()->getRepository(User::class);
//        $response = array("Volvo1", "BMW", "Toyota");
//        $user = $repository->findAll();

        $data = array(
            'username' => "magento",
            'password' => 'magento2'
        );

        $json = json_encode($data);

        $ch = curl_init('http://m22.qbo.tech:8014/rest/V1/integration/admin/token');

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 29);

        if (curl_errno($ch)) {

            $response['status_time'] = '501';
            $response['connect_time'] = 'Time out';
            $view = $this->view($response, '501');
            return $this->handleView($view);
        }

        $result = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        $jsonToken = json_decode($result, true);
        $chProduct = curl_init('http://m22.qbo.tech:8014/rest/V1/products/MT01');
        curl_setopt($chProduct, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $jsonToken . ''));

        curl_setopt($chProduct, CURLOPT_HTTPGET, 1);
        curl_setopt($chProduct, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chProduct, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($chProduct, CURLOPT_TIMEOUT, 29);

        if (curl_errno($chProduct)) {

            $response['status_time'] = '501';
            $response['connect_time'] = 'Time out';
            $view = $this->view($response, '501');
            return $this->handleView($view);
        }

        $result1 = curl_exec($chProduct);
        $status1 = curl_getinfo($chProduct, CURLINFO_HTTP_CODE);

        curl_close($chProduct);

        $jsonResponse = json_decode($result1, true);
        $view = $this->view($jsonResponse, $status1);
        return $this->handleView($view);
    }

}
