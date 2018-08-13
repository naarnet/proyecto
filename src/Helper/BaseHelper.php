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

        if (curl_errno($ch)) {
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
