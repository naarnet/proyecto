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
    const URL_STORE_TOKEN = 'rest/V1/integration/admin/token';

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
        if (curl_errno($ch)) {
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
        $json_token = curl_exec($ch);
        curl_close($ch);
        $isJson = is_string($json_token) && is_array(json_decode($json_token, true)) ? true : false;
        if ($isJson) {
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
        if (curl_errno($chCategories)) {
            return false;
        }
        return $chCategories;
    }

    /**
     * Function To get Credential to connect Basic OAUTH
     *
     * @param [array] $formData
     * @return array
     */
    public function getFormCredentialData($formData = null)
    {
        $data = null;
        if (!empty($formData)) {
            if ($formData['credential'] && $formData['credential'] == 'basic_oauth') {
                if (isset($formData['basic_oauth']) && !empty($formData['basic_oauth'])) {
                    $data['username'] = isset($formData['basic_oauth']['oauth_username']) ? $formData['basic_oauth']['oauth_username'] : '';
                    $data['password'] = isset($formData['basic_oauth']['oauth_password']) ? $formData['basic_oauth']['oauth_password'] : '';
                }
            }
        }
        return $data;
    }

    /**
     * Check if exist credential to connect function
     *
     * @param [type] $data
     * @return boolean
     */
    public function hasCredentialsData($data)
    {
        if(
          isset($data['username']) && !empty($data['username']) && 
          isset($data['password']) && !empty($data['password'])
        ) {
            return true;
        }
        return false;
    }

    /**
     * Ger Url From FormData function
     *
     * @param [type] $formData
     * @return string
     */
    public function getUrlData($formData)
    {
        if ($formData['url']) {
            $url = $formData['url'] . self::URL_STORE_TOKEN;
        }
        return $url;
    }

}
