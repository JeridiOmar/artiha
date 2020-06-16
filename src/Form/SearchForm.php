<?php


namespace App\Form;


use App\Home\SearchEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('motCle', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "  What do you want to search ...",
                    "class" => "search-field p-1"
                ]
            ])
            ->add('choice', ChoiceType::class, [
                'label'=>false,
                'choices' => [
                    'Users' => 'Users',
                    'Tags' => 'Tags',
                    'Post Title' => 'Post Title',
                    'Description' => 'Description',

                ],
                "attr"=>[
                    'class'=>"button-search"
                ]
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchEntity::class,
            'method' => 'GET'
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }

}