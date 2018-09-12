<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StoreCredentialRepository")
 */
class StoreCredential
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
    private $api_url;

    /**
     * @ManyToOne(targetEntity="Store",inversedBy="store_credential",cascade={"persist", "remove"})
     * @JoinColumn(referencedColumnName="id", nullable=false)
     */
//    protected $store;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    public function getId()
    {
        return $this->id;
    }

    public function getApiUrl()
    {
        return $this->api_url;
    }

    public function setApiUrl($api_url)
    {
        $this->api_url = $api_url;

        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * 
     * @return type
     */
    public function getStore()
    {

        return $this->store;
    }

    /**
     * 
     * @param type $store
     * @return $this
     */
    public function setStore($store)
    {
        $this->store = $store;

        return $this;
    }

    /**
     * 
     * @return type
     */
    public function __toString()
    {
        return (string) $this->api_url;
    }

}
