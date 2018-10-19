<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChannelConnectorRepository")
 */
class ChannelConnector
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="Marketplace")
     * @JoinColumn(name="marketplace_id",referencedColumnName="id" ,onDelete="CASCADE")
     */
    private $marketplace;

    /**
     * @ORM\OneToMany(targetEntity="ChannelConnectorEntityAttribute", mappedBy="channelConnector",cascade={"remove"})
     */
    protected $channelConnector_entity_attribute;

    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="user_id",referencedColumnName="id" ,onDelete="CASCADE",nullable=true)
     */
    protected $user;

    /**
     * @ManyToOne(targetEntity="Conexion")
     * @JoinColumn(name="conexion_id",referencedColumnName="id" ,onDelete="CASCADE",nullable=false)
     */
    protected $conexion;

    public function __construct()
    {
        $this->channelConnector_entity_attribute = new ArrayCollection();
    }

    /**
     * 
     * @return int
     */
    public function getId()
    {
        return $this->id;
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

    public function getChannelConnectorEntityAttribute()
    {
        return $this->channelConnector_entity_attribute;
    }

    public function setChannelConnectorEntityAttribute($channelConnector_entity_attribute)
    {
        $est[] = $channelConnector_entity_attribute;
        $this->channelConnector_entity_attribute = $est;
    }

    /**
     * @param type $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * 
     * @return type
     */
    public function getUser()
    {
        return $this->user;
    }

    public function getUrl()
    {
        $url = null;
        foreach ($this->channelConnector_entity_attribute as $attribute) {
            if ($attribute->getAttributeCode() === 'url') {
                $url = $attribute->getValue();
                break;
            }
        }
        return $url;
    }

    public function setUrl($url)
    {
        foreach ($this->channelConnector_entity_attribute as $attribute) {
            if ($attribute->getAttributeCode() === 'url' && $attribute->getValue() !== $url) {
                $attribute->setValue($url);
                break;
            }
        }
    }

    public function getBasicOauth()
    {
        $oauth = [];
        $oauth['oauth_username'] = $this->getOauthUsername();
        $oauth['oauth_password'] = $this->getOauthPassword();
        return $oauth;
    }

    public function setBasicOauth($oauth)
    {
        return $this;
    }

    public function getOauthUsername()
    {
        $username = '';
        foreach ($this->channelConnector_entity_attribute as $attribute)
        {
            if ($attribute->getAttributeCode() === 'oauth_username')
            {
                $username = $attribute->getValue();
                break;
            }
        }
        return $username;
    }

    public function setOauthUser($oauth_username)
    {
        foreach ($this->channelConnector_entity_attribute as $attribute)
        {
            if ($attribute->getAttributeCode() === 'oauth_username')
            {
                $attribute->setValue($oauth_username);
                break;
            }
        }
    }

    public function getOauthPassword()
    {
        $password = '';
        foreach ($this->channelConnector_entity_attribute as $attribute)
        {
            if ($attribute->getAttributeCode() === 'oauth_password')
            {
                $password = $attribute->getValue();
                break;
            }
        }
        return $password;
    }

    public function setOauthPassword($password)
    {
        foreach ($this->channelConnector_entity_attribute as $attribute)
        {
            if ($attribute->getAttributeCode() === 'oauth_password')
            {
                $attribute->setValue($password);
                break;
            }
        }
    }

    public function getCredential()
    {
        
    }

    public function setCredential($credential)
    {

        return $this;
    }

    /**
     * @param type $conexion
     */
    public function setConexion($conexion)
    {
        $this->conexion = $conexion;
    }

    /**
     * @return type
     */
    public function getConexion()
    {
        return $this->conexion;
    }

    public function __toString()
    {
        return (string)$this->marketplace;
    }

}
