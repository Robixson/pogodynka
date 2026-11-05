<?php

namespace App\Form;

use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('city', TextType::class, [
                'label' => 'Miasto',
            ])
            ->add('country', CountryType::class, [
                'label' => 'Państwo',
            ])
            ->add('latitude', TextType::class, [
                'label' => 'Szerokość geograficzna',
                'attr' => [
                    'type' => 'number',
                    'step' => 'any',
                ],
            ])
            ->add('longitude', TextType::class, [
                'label' => 'Długość geograficzna',
                'attr' => [
                    'type' => 'number',
                    'step' => 'any',
                ],
            ])
            ->add('province', TextType::class, [
                'label' => 'Województwo',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}