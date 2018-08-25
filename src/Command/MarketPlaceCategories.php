<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Entity\Marketplace;
use Psr\Log\LoggerInterface;
use App\Entity\MarketplaceCategory;

class MarketPlaceCategories extends Command
{

    private $_container;

    /**
     *
     * @var Psr\Log\LoggerInterface 
     */
    protected $_logger;

    /**
     *
     * @var type 
     */
    protected $_entityManager;
    
    /**
     * @param ContainerInterface $container
     * @param LoggerInterface $logger
     * @param type $name
     */
    public function __construct(
        ContainerInterface $container,
        LoggerInterface $logger,
        $name = null
    ) {
        $this->_logger = $logger;
        $this->_entityManager = $container->get('doctrine')->getManager();
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
                // the name of the command (the part after "bin/console")
                ->setName('app:import-categories')
                // the short description shown while running "php bin/console list"
                ->setDescription('Creates a new user.')
                ->addArgument('marketplace', InputArgument::REQUIRED, 'The name of the marketplace.')
                // the full command description shown when running the command with
                // the "--help" option
                ->setHelp('This command allows you to import categories from marketplace...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'Importing Categories Marketplace',
            '============',
            '',
        ]);

        $output->writeln('Marketplace: ' . $input->getArgument('marketplace'));
        $marketName = $input->getArgument('marketplace');

        //Get entity manager from container
        $marketPlace = $this->_entityManager->getRepository(Marketplace::class)->findOneBy(array('name' => $marketName));
        if ($marketPlace)
        {
            $this->importCategories($marketPlace);
            $output->writeln('Marketplace Categories Imported');
        } else
        {
            $output->writeln('Do not exist Marketplace');
        }
    }

    /**
     * Import Categories from MarketPlace
     * @return RedirectResponse
     */
    public function importCategories($marketPlace)
    {
        $categories = $this->getMarketPlaceCategory();
        foreach ($categories as $category) {
            $this->_logger->log(100, print_r($category, true));
            $this->createCategoryByCollection($category, $marketPlace, null,null);
        }
    }

    public function setStoreToSave($category, $marketPlace, $category_parent_id = null,$category_parent_name = null)
    {
        $saveStore = true;
        $storeCategory = $this->_entityManager->getRepository(MarketplaceCategory::class)
                ->findOneBy(array('marketplace_category_id' => $category['id']));
        
        
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

        if ($saveStore && $marketPlace) {
            $storeCategory->setName($category['name']);
            $storeCategory->setMarketplaceCategoryId($category['id']);
            $storeCategory->setMarketplace($marketPlace);
            $storeCategory->setIsActive(true);
            $storeCategory->setParentCategoryId($category_parent_id);
            $storeCategory->setParentCategoryName($category_parent_name);

            try {
                $this->_entityManager->persist($storeCategory);
                $this->_entityManager->flush();
                $this->_logger->log(100, print_r('Salvado', true));
            } catch (\Exception $ex) {
                $this->_logger->log(100, print_r($ex->getMessage(), true));
            }
        }
          
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

        if (curl_errno($ch))
        {
            $response['status_time'] = '501';
            $response['connect_time'] = 'Time out';
        }

        $json_response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $json_data = json_decode($json_response, true);
        return $json_data;
    }
    
    public function createCategoryByCollection($category, $marketPlace, $parentCategoryId = null, $parentCategoryName = null)
    {
        if (isset($category['id']) && !empty($category['id']))
        {
            if ($this->isStoreCategoryReadyToSave($category))
            {
                $this->setStoreToSave($category, $marketPlace, $parentCategoryId, $parentCategoryName);
            }
            $childrenData = $this->getChildrenMarketPlaceCategory($category['id']);
            $parentCategoryId = $category['id'];
            $parentCategoryName = $category['name'];


            if (isset($childrenData['children_categories']) && !empty($childrenData['children_categories']))
            {
                $childrenCategories = $childrenData['children_categories'];
                foreach ($childrenCategories as $children)
                {
                    $this->createCategoryByCollection($children, $marketPlace, $parentCategoryId, $parentCategoryName);
                }
            }
        }
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

}
