<?php

/**
 * MMDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDMMM
 * MDDDDDDDDDDDDDNNDDDDDDDDDDDDDDDDD=.DDDDDDDDDDDDDDDDDDDDDDDMM
 * MDDDDDDDDDDDD===8NDDDDDDDDDDDDDDD=.NDDDDDDDDDDDDDDDDDDDDDDMM
 * DDDDDDDDDN===+N====NDDDDDDDDDDDDD=.DDDDDDDDDDDDDDDDDDDDDDDDM
 * DDDDDDD$DN=8DDDDDD=~~~DDDDDDDDDND=.NDDDDDNDNDDDDDDDDDDDDDDDM
 * DDDDDDD+===NDDDDDDDDN~~N........8$........D ........DDDDDDDM
 * DDDDDDD+=D+===NDDDDDN~~N.?DDDDDDDDDDDDDD:.D .DDDDD .DDDDDDDN
 * DDDDDDD++DDDN===DDDDD~~N.?DDDDDDDDDDDDDD:.D .DDDDD .DDDDDDDD
 * DDDDDDD++DDDDD==DDDDN~~N.?DDDDDDDDDDDDDD:.D .DDDDD .DDDDDDDN
 * DDDDDDD++DDDDD==DDDDD~~N.... ...8$........D ........DDDDDDDM
 * DDDDDDD$===8DD==DD~~~~DDDDDDDDN.IDDDDDDDDDDDNDDDDDDNDDDDDDDM
 * NDDDDDDDDD===D====~NDDDDDD?DNNN.IDNODDDDDDDDN?DNNDDDDDDDDDDM
 * MDDDDDDDDDDDDD==8DDDDDDDDDDDDDN.IDDDNDDDDDDDDNDDNDDDDDDDDDMM
 * MDDDDDDDDDDDDDDDDDDDDDDDDDDDDDN.IDDDDDDDDDDDDDDDDDDDDDDDDDMM
 * MMDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDMMM
 *
 * @author Néstor Alain <alain@qbo.tech>
 * @category qbo
 * @package qbo\Afiliate\
 * @copyright qbo (http://www.qbo.tech)
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * 
 * © 2016 QBO DIGITAL SOLUTIONS. 
 *
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StoreRepository")
 */
class Store
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull()
     */
    private $name;

    /**
     * @ManyToOne(targetEntity="StoreEntityRole")
     * @JoinColumn(name="store_entity_role_id",referencedColumnName="id" ,onDelete="CASCADE",nullable=true)
     */
    protected $store_entity_role;

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

    /**
     * @ORM\OneToMany(targetEntity="StoreEntityAttribute", mappedBy="store",cascade={"remove"})
     */
    protected $store_entity_attribute;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Country()
     */
    protected $country;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $language;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $currency;

    public function __construct()
    {
        $this->store_credential = new ArrayCollection();
        $this->store_entity_attribute = new ArrayCollection();
    }

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

    public function __toString()
    {
        return (string) $this->getName();
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

    /**
     * @param type $store_entity_role
     */
    public function setStoreEntityRole($store_entity_role)
    {
        $this->store_entity_role = $store_entity_role;
    }

    /**
     * @return type
     */
    public function getStoreEntityRole()
    {
        return $this->store_entity_role;
    }

    /**
     * @return type
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param type $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return type
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param type $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return type
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param type $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    public function getStoreEntityAttribute()
    {
        return $this->store_entity_attribute;
    }

    public function setStoreEntityAttribute($store_entity_attribute)
    {
        $est[] = $store_entity_attribute;
        $this->store_entity_attribute = $est;
    }
    
    public function getUrl()
    {
        $url = null;
        foreach ($this->store_entity_attribute as $attribute)
        {
            if ($attribute->getAttributeCode() === 'url')
            {
                $url = $attribute->getValue();
                break;
            }
        }
        return $url;
    }

    public function setUrl($url)
    {
        foreach ($this->store_entity_attribute as $attribute)
        {
            if ($attribute->getAttributeCode() === 'url' && $attribute->getValue() !== $url)
            {
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

    public function getCredential()
    {
        
    }

    public function setCredential($credential)
    {

        return $this;
    }

    public function getOauthUsername()
    {
        $username = '';
        foreach ($this->store_entity_attribute as $attribute)
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
        foreach ($this->store_entity_attribute as $attribute)
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
        foreach ($this->store_entity_attribute as $attribute)
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
        foreach ($this->store_entity_attribute as $attribute)
        {
            if ($attribute->getAttributeCode() === 'oauth_password')
            {
                $attribute->setValue($password);
                break;
            }
        }
    }

}
