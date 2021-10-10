<?php

namespace App\Form;

use App\Entity\Place;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlaceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        {
            $builder
                -> add('name', TextType::class, [
                    'label' => 'Nom du lieu :',
                    'required' => true])
                -> add('street', TextType::class, [
                    'label' => 'Rue :',
                    'required' => 'true',])
                -> add('latitude', NumberType::class, [
                    'label' => 'Latitude :',
                    'required' => 'true',])
                -> add('longitude', NumberType::class, [
                    'label' => 'Longitude :',
                    'required' => 'true',]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Place::class,
        ]);
    }
}