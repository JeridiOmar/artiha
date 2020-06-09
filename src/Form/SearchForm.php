<?php


namespace App\Form;


use App\Entity\Category;
use App\Home\SearchHome;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categories',EntityType::class,[
                'required'=>false,
                'label'=>false,
                'class'=>Category::class,
                'expanded'=>true,
                'multiple'=>true,
                'attr'=>[
                    'class'=>'styled'
                ]
            ])
            ->add('min',IntegerType::class,[
                'required'=>false,
                'attr'=>[
                    'placeholder'=>'likes min ',
                    'class'=>'form-group-edit',
                ]
            ])
            ->add('max',IntegerType::class,[
                'required'=>false,
                'attr'=>[
                    'placeholder'=>'likes max ',
                    'class'=>'form-group-edit',

                ]
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=>SearchHome::class,
            'method'=>'GET'
        ]);
    }

    public function getBlockPrefix()
    {
     return '';
    }

}