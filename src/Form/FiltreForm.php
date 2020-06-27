<?php


namespace App\Form;


use App\Entity\Category;
use App\Search\SearchHome;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiltreForm extends AbstractType
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
                    'class'=>'form-group-edit search-field',
                ]
            ])
            ->add('max',IntegerType::class,[
                'required'=>false,
                'attr'=>[
                    'placeholder'=>'likes max ',
                    'class'=>'form-group-edit search-field',


                ]
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=>SearchHome::class,
            'method'=>'GET',
            'csrf_protection'=>false
        ]);
    }

    public function getBlockPrefix()
    {
     return '';
    }

}