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
     * @JoinColumn(name="store_entity_role_id",referencedColumnName="id" ,onDelete="CASCADE",nullable=false)
     */
    protected $store_entity_role;
    
    /**
     * @Assert\NotNull()
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="user_id",referencedColumnName="id" ,onDelete="CASCADE",nullable=false)
     */
    protected $user;
    
    /**
     * @ManyToOne(targetEntity="Conexion")
     * @JoinColumn(name="conexion_id",referencedColumnName="id" ,onDelete="CASCADE",nullable=false)
     */
    protected $conexion;
    
    
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
     * 
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
     * 
     * @param type $conexion
     */
    public function setConexion($conexion)
    {
        $this->conexion = $conexion;
    }

    /**
     * 
     * @return type
     */
    public function getConexion()
    {
        return $this->conexion;
    }
    
    /**
     * 
     * @param type $store_entity_role
     */
    public function setStoreEntityRole($store_entity_role)
    {
        $this->store_entity_role = $store_entity_role;
    }

    /**
     * 
     * @return type
     */
    public function getStoreEntityRole()
    {
        return $this->store_entity_role;
    }

}
