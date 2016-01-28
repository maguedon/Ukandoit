<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\PossessedDeviceType;
use AppBundle\Entity\DeviceType as DeviceType;

class NewPossessedDeviceType extends PossessedDeviceType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->remove('accessTokenJawbone')
            ->remove('userIdWithings')
            ->remove('accessTokenKeyWithings')
            ->remove('accessTokenSecretWithings')
            ->remove('url')
            ->remove('user')
            ->add('deviceType', 'entity', array(
                'class' => 'AppBundle:DeviceType',
                'label' => "Objet connecté : "))
            ->add('submit', 'submit', array(
                'label' => 'Ajouter'
                ))
            ;
    }
}
