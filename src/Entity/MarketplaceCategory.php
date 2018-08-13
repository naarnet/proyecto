<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MarketplaceCategoryRepository")
 */
class MarketplaceCategory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_active;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $marketplace_category_id;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $parent_category_id;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $parent_category_name;

    /**
     * @ManyToOne(targetEntity="Marketplace")
     * @JoinColumn(name="marketplace_id",referencedColumnName="id" ,onDelete="CASCADE")
     */
    private $marketplace;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    public function getIsActive()
    {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active)
    {
        $this->is_active = $is_active;

        return $this;
    }

    public function getMarketplaceCategoryId()
    {
        return $this->marketplace_category_id;
    }

    public function setMarketplaceCategoryId($marketplace_category_id)
    {
        $this->marketplace_category_id = $marketplace_category_id;

        return $this;
    }
    public function getParentCategoryId()
    {
        return $this->parent_category_id;
    }

    public function setParentCategoryId($parent_category_id)
    {
        $this->parent_category_id= $parent_category_id;

        return $this;
    }
    public function getParentCategoryName()
    {
        return $this->parent_category_name;
    }

    public function setParentCategoryName($parent_category_name)
    {
        $this->parent_category_name = $parent_category_name;

        return $this;
    }

    public function getMarketplace()
    {
        return $this->marketplace;
    }

    public function setMarketplace($marketplace)
    {
        $this->marketplace = $marketplace;

        return $this;
    }
    
    public function __toString()
    {
        return (string) $this->getParentCategoryName().'-'.$this->getName();
    }
}
