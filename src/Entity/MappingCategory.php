<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MappingCategoryRepository")
 */
class MappingCategory
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="StoreCategory")
     * @JoinColumn(name="store_category_id",referencedColumnName="id" ,onDelete="CASCADE")
     */
    private $store_category;

    /**
     * @ManyToOne(targetEntity="StoreMigration")
     * @JoinColumn(name="store_migration_id",referencedColumnName="id" ,onDelete="CASCADE")
     */
    private $store_migration;

    /**
     * @ManyToOne(targetEntity="MarketplaceCategory")
     * @JoinColumn(name="marketplace_migration_id",referencedColumnName="id" ,onDelete="CASCADE")
     */
    private $marketplace_category;

    /**
     * @ORM\Column(type="boolean")
     */
    private $published;

    public function getId()
    {
        return $this->id;
    }

    public function getStoreCategory()
    {
        return $this->store_category;
    }

    public function setStoreCategory($store_category)
    {
        $this->store_category = $store_category;

        return $this;
    }

    public function getStoreMigration()
    {
        return $this->store_migration;
    }

    public function setStoreMigration($store_migration)
    {
        $this->store_migration = $store_migration;

        return $this;
    }

    public function getMarketplaceCategory()
    {
        return $this->marketplace_category;
    }

    public function setMarketplaceCategory($marketplace_category)
    {
        $this->marketplace_category = $marketplace_category;

        return $this;
    }

    public function getPublished()
    {
        return $this->published;
    }

    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    public function __toString()
    {
        if ($this->getStoreCategory() && $this->getMarketplaceCategory())
        {
            return (string) $this->getStoreCategory()->getName() . '-' . $this->getMarketplaceCategory()->getName();
        }
        return (string) '';
    }

}
