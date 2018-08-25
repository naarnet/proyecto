<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StoreCredentialType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('api_url', TextType::class, array('label' => 'Api Url'))
                ->add('username', TextType::class, array('label' => 'UserName'))
                ->add('password', TextType::class, array('label' => 'Password'))
//                ->add('store', CollectionType::class, array(
//                    'label' => 'Tienda',
//                    'required' => true,
////                    'model_manager' => $this->getModelManager(),
////                    'class' => App\Entity\StoreCredential::class,
//                    'help' => 'Asigne una Tienda'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => \App\Entity\StoreCredential::class,
        ]);
    }
}
