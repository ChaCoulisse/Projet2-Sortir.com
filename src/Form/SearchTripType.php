<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Trip;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchTripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('campus',EntityType::class,[
                'mapped' => false,
                'label'=>'Campus :',
                'class'=> Campus::class,
                'choice_label' => 'name'
            ])
            ->add('name', TextType::class, [
                'label' => 'Le nom de la série contient',
                'required' => false
            ])
            ->add('start', DateType::class, [
                'mapped' => false,
                'html5' => true,
                'widget'=>'single_text',
                'label' => 'Entre'
            ])
            ->add('end', DateType::class, [
                'mapped' => false,
                'html5' => true,
                'widget'=>'single_text',
                'label' => 'et'
            ])
            ->add('organizer', CheckboxType::class, [
                'mapped' => false,
                'label'    => 'Sorties dont je suis l\'organisateur/trice',
                'required' => false,
            ])
            ->add('participant', CheckboxType::class, [
                'mapped' => false,
                'label'    => 'Sorties auxquelles je suis inscrit/e',
                'required' => false,
            ])
            ->add('notParticipant', CheckboxType::class, [
                'mapped' => false,
                'label'    => 'Sorties auxquelles je ne suis pas inscrit/e',
                'required' => false,
            ])
            ->add('state', CheckboxType::class, [
                'mapped' => false,
                'label'    => 'Sorties passées',
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Rechercher'
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
        ]);
    }
}
