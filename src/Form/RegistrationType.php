<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class RegistrationType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('nom',TextType::class,[
                'label'=>'nom',
                'attr'=>[
                    'placeholder'=>"nom"
                ]
            ])
            ->add('prenom',TextType::class,[
                'label'=>'prenom',
                'attr'=>[
                    'placeholder'=>"prenom"
                ]
            ])
            ->add('Email',TextType::class,[
                'label'=>'Email',
                'attr'=>[
                    'placeholder'=>"Email"
                ]
            ])
            ->add('password',PasswordType::class,[
                'label'=>'mot de passe',
                'attr'=>[
                    'placeholder'=>"mot de passe",

                ]
            ])
            ->add('confirm_password',PasswordType::class,[
                'label'=>'confirmer le mot de passe',
                'attr'=>[
                    'placeholder'=>"mot de passe",

                ]
            ])
            ->add('sexe', ChoiceType::class, [
                'choices' => [
                    'homme' => 'homme',
                    'femme' => 'femme',

                ]])

            ->add('birthday',BirthdayType::class)
            ->add('numTel',TextType::class,[
                'label'=>'Numero de telephone',
                'attr'=>[
                    'placeholder'=>"Numero de telephone"
                ]
            ])
            ->add('ProfilePicture', FileType::class, [
                'label' => 'Photo de profile',
                'constraints' => [
                    new Image()
                ],

                // unmapped means that this field is not associated to any entity property
                'mapped' => false
            ])
        ->add('Enregistrer',SubmitType::class,[
            'attr'=>[
                'class'=>'btn btn-success'
            ]
        ])
            //->add('bio')
            //->add('profilePicture')
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
