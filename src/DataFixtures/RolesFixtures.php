<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class RolesFixtures extends Fixture implements OrderedFixtureInterface
{

    /**
     * 
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        //Administrador general de TANTAN
        $roleAdmin = new Role();
        $roleAdmin->setName('ROLE_ADMIN');
        $roleAdmin->setDescription('ADMINISTRADOR GENERAL');
        $manager->persist($roleAdmin);
        $this->addReference('ROLE_ADMIN', $roleAdmin);
        $manager->flush();
        
        $roleStore = new Role();
        $roleStore->setName('ROLE_STORE');
        $roleStore->setDescription('ADMINISTRADOR DE TIENDA');
        $manager->persist($roleStore);
        $this->addReference('ROLE_STORE', $roleStore);
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }

}
