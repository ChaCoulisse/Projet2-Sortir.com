<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditAccountFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname', TextType::class, [
                'label' => 'Nom de famille :',
                'required' => false,
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom :',
                'required' => false,
            ])
            ->add('phone', TextType::class, [
                'label' => 'Numéro de téléphone :',
                'required' => false,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email :',
                'required' => false,
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez votre mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères',
                        'max' => 4096,
                    ]),
                ],
                'invalid_message' => 'Mot de passe incorrect',
                'required' => true,
                'first_options' => ['label' => 'Mot de passe actuel'],
                'second_options' => ['label' => 'Répéter mot de passe'],
            ])
            ->add('userName',TextType::class, [
                'label' => 'Pseudo :',
                'required' => false,
            ])
            ->add('campus', EntityType::class, [
                'label' => 'Campus :',
                'class' => 'App\Entity\Campus',
                'choice_label' => 'name',
                'required' => false,
            ])
            //->add('picture', FileType::class, [
            //    'label' => 'Photo de profil',
            //    'mapped' => false,
            //    'required' => false,
            //])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}