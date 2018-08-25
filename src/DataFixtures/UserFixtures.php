<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements OrderedFixtureInterface
{

    /**
     *
     * @var type 
     */
    private $encoder;

    /**
     * 
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * 
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $admin->setName('Super Administrador');
        $admin->setLastName('del Sistema');
        $admin->setEmail('naarnet10@gmail.com');
        $password = $this->encoder->encodePassword($admin, 'ronaldinho');
        $admin->setPassword($password);
        $admin->addRole($this->getReference('ROLE_ADMIN'));

        $this->addReference('admin', $admin);
        $manager->persist($admin);
        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }

}
