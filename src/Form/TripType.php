<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Place;
use App\Entity\State;
use App\Entity\Trip;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name', TextType::class,[
                'label'=> 'Nom de la sortie :',
                'required'=>true
            ])
            ->add('startHour', DateType::class,[
                'label'=> 'Date :',
                'required'=>true,
                'html5' =>true,
                'widget'=>'single_text',

            ])
            ->add('duration', TimeType::class,[
                'label'=> 'Durée :',
                'widget'=>'single_text',
                'required'=>true,
            ] )
            ->add('limitDate', DateType::class,[
                'label' => 'Date limite d\'inscription',
                'widget'=>'single_text',
                'required'=>true,
                'html5' =>true,

            ])
            ->add('limitedPlace', IntegerType::class, [
                'label' => 'Nombre maximum de participants ',
                'required' => true,
            ])
            ->add('infoTrip', TextareaType::class, [
                'label'=>'Description et infos :',
                'required' => false,
            ])
            ->add('campus', EntityType::class, [
                'label'=>'Campus :',
                'class'=>Campus::class,
                'choice_label'=>'name',
                'required'=>'true',
            ])
            ->add('state', EntityType::class, [
                'class'=>State::class,
                'choice_label'=>function(State $state ){
                    return $state->getWording();
                },
                'required'=>'true',
                'attr' => array(
                    'readonly' => true,
                ),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
        ]);
    }
}
