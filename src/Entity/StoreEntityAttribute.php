<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StoreEntityAttributeRepository")
 */
class StoreEntityAttribute
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="Store",inversedBy="store_entity_attribute")
     * @JoinColumn(referencedColumnName="id", nullable=false)
     */
    private $store;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $attribute_code;

    /**
     * @OneToOne(targetEntity="StoreEntityAttributeValue")
     */
//    private $storeEntityAttributeValue;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $value;

    public function getId()
    {
        return $this->id;
    }

    public function getStore()
    {
        return $this->store;
    }

    public function setStore($store)
    {
        $this->store = $store;

        return $this;
    }

    public function getAttributeCode()
    {
        return $this->attribute_code;
    }

    public function setAttributeCode($attribute_code)
    {
        $this->attribute_code = $attribute_code;

        return $this;
    }

//    public function getStoreEntityAttributeValue()
//    {
//        return $this->storeEntityAttributeValue;
//    }

//    public function setStoreEntityAttributeValue($storeEntityAttributeValue)
//    {
//        $this->storeEntityAttributeValue = $storeEntityAttributeValue;
//        return $this;
//    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

//    public function __toString()
//    {
//        return $this->value;
//    }
    /**
     * 
     * @return type
     */
    public function __toString()
    {
        return (string) $this->attribute_code;
    }

}
