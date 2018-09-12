<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Helper;

use Psr\Log\LoggerInterface;

class BaseHelper
{
    const DATA_USERNAME = 'username';
    const DATA_PASSWORD = 'password';
    const URL_STORE_TOKEN = 'rest/V1/integration/admin/token';
    const URL_STORE_CATEGORIES = 'rest/V1/categories';
    
    protected $_logger;

    /**
     * 
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->_logger = $logger;
    }
    
    
    /**
     * Return Array credentials to connect
     * @param type $store
     * @return type
     */
    public function getStoreCredentialData($store)
    {
        $data = [];
        if (!is_null($store))
        {
            $data = array(
                self::DATA_USERNAME => $store->getOauthUsername(),
                self::DATA_PASSWORD => $store->getOauthPassword()
            );
        }

        return $data;
    }

    /**
     * Return url Concat with api route
     * @param type $storeUrl
     * @return string
     */
    public function getStoreUrlToAccessToken($storeUrl)
    {
        $url = '';
        if (!empty($storeUrl) && $storeUrl !== '')
        {
            $url = $storeUrl . self::URL_STORE_TOKEN;
        }
        return $url;
    }
    
    /**
     * Return Store Api Categories Url
     * @param type $url
     * @return string
     */
    public function getStoreCategoryUrl($url)
    {
        $catUrl = '';
        if (!empty($url) && $url !== '')
        {
            $catUrl = $url . self::URL_STORE_CATEGORIES;
        }
        return $catUrl;
    }

    //Connect to Marketplace
    
    /**
     * Return MarketPlace Categories
     * @return type
     */
    public function getMarketPlaceCategory()
    {

        $ch = curl_init('https://api.mercadolibre.com/sites/MLM/categories');
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 29);

        if (curl_errno($ch)) {
            $response['status_time'] = '501';
            $response['connect_time'] = 'Time out';
        }

        $json_response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $json_data = json_decode($json_response, true);
        return $json_data;
    }

    /**
     * Return marketplace categories children
     * @param type $categoryId
     * @return type
     */
    public function getChildrenMarketPlaceCategory($categoryId)
    {
        $url = 'https://api.mercadolibre.com/categories/' . $categoryId;
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 29);

        if (curl_errno($ch))
        {
            $response['status_time'] = '501';
            $response['connect_time'] = 'Time out';
            $response['connect_time'] = 'Time out';
            $response['success'] = false;
        }

        $json_response = curl_exec($ch);
        curl_close($ch);

        $json_data = json_decode($json_response, true);
        return $json_data;
    }
    
}
