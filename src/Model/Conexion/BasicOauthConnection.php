<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Conexion;

use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Helper\BaseHelper;

/**
 * Description of BasicOauthConexion
 *
 * @author qbo
 */
class BasicOauthConnection
{

    const CONTENT_TYPE_JSON = 'Content-type: application/json';
    const HEADER_BEARER = 'Authorization: Bearer ';
    
    protected $_logger;
    private $_em;
    protected $_container;
    
    /**
     * @var App\Helper\BaseHelper; 
     */
    protected $_helperData;

    /**
     * 
     * @param LoggerInterface $logger
     * @param EntityManagerInterface $em
     * @param ContainerInterface $container
     */
    public function __construct(
        LoggerInterface $logger,
        EntityManagerInterface $em,
        ContainerInterface $container,
        BaseHelper $baseHelper    
    ) {
        $this->_logger = $logger;
        $this->_em = $em;
        $this->_container = $container;
        $this->_helperData = $baseHelper;
    }

    /**
     * Return Store token connection
     * @param type $data
     * @param type $url
     * @return boolean
     */
    public function getStoreConnection($data, $url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(self::CONTENT_TYPE_JSON));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 29);

        if (curl_errno($ch))
        {
            return false;
        }
        return $ch;
    }

    /**
     * Return status connection
     * @param type $formData
     * @return boolean
     */
    public function getConnectionStatus($formData = null)
    {
        $data = $this->getFormCredentialData($formData);
        $url = $this->getUrlData($formData);
        $ch = $this->getStoreConnection($data, $url);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);
        if ($status !== 200)
        {
            return false;
        }
        return true;
    }
    
    /**
     * Return Store Category Connection
     * @param type $url
     * @param type $jsonToken
     */
    public function getStoreCategoryConnection($url, $jsonToken)
    {
        //Get Store Category Url to request
        $urlCategories = $this->_helperData->getStoreCategoryUrl($url);

        $chCategories = curl_init($urlCategories);
        curl_setopt($chCategories, CURLOPT_HTTPHEADER, array(self::HEADER_BEARER . $jsonToken . ''));
        curl_setopt($chCategories, CURLOPT_HTTPGET, 1);
        curl_setopt($chCategories, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chCategories, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($chCategories, CURLOPT_TIMEOUT, 29);

        if (curl_errno($chCategories))
        {
            return false;
        }
        return $chCategories;
    }

    public function getFormCredentialData($formData = null)
    {
        $data = null;
        if (!empty($formData))
        {
            if ($formData['credential'] && $formData['credential'] == 'basic_oauth')
            {
                if (isset($formData['basic_oauth']) && !empty($formData['basic_oauth']))
                {
                    $data['username'] = isset($formData['basic_oauth']['oauth_username']) ? $formData['basic_oauth']['oauth_username'] : '';
                    $data['password'] = isset($formData['basic_oauth']['oauth_password']) ? $formData['basic_oauth']['oauth_password'] : '';
                }
            }
        }
        return $data;
    }

    public function getUrlData($formData)
    {
        if ($formData['url'])
        {
            $url = $formData['url'];
        }
        return $url;
    }

}
