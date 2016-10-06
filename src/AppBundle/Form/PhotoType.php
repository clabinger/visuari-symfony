<?php

// src/AppBundle/Form/PhotoType.php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
// use Symfony\Component\Form\Extension\Core\Type\DateType;


class PhotoType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('size_original', TextType::class, Array('label'=>'original size'))
            ->add('size_medium', TextType::class, Array('label'=>'medium size'))
            ->add('size_thumb', TextType::class, Array('label'=>'thumb size'))
            // ->add('save', SubmitType::class, Array('label'=>'photo.label.create_collection'))
        ;
    }


	public function configureOptions(OptionsResolver $resolver)
	{
	    $resolver->setDefaults(array(
	        'data_class' => 'AppBundle\Entity\Photo',
	    ));
	}

}