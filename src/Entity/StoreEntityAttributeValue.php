<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StoreEntityAttributeValueRepository")
 */
class StoreEntityAttributeValue
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
    private $value;

    /**
     * @OneToOne(targetEntity="StoreEntityAttribute")
     * @ORM\JoinColumn(name="store_entity_attribute_id", referencedColumnName="id")
     */
//    private $store_entity_attribute;

    public function getId()
    {
        return $this->id;
    }

//    public function getStoreEntityAttribute()
//    {
//        return $this->store_entity_attribute;
//    }

//    public function setStoreEntityAttribute($store_entity_attribute)
//    {
//        $this->store_entity_attribute = $store_entity_attribute;
//
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

    public function __toString()
    {
        return $this->value;
    }

}
