<?php

// src/AppBundle/Form/CollectionType.php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
// use Symfony\Component\Form\Extension\Core\Type\DateType;


class CollectionType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name', TextType::class, Array('label'=>'collection.label.name'))
            ->add('description', TextType::class, Array('label'=>'collection.label.description'))
            ->add('public', CheckboxType::class, Array('label'=>'collection.label.make_public', 'required'=>false))
            // ->add('save', SubmitType::class, Array('label'=>'collection.label.create_collection'))
        ;
    }


	public function configureOptions(OptionsResolver $resolver)
	{
	    $resolver->setDefaults(array(
	        'data_class' => 'AppBundle\Entity\Collection',
	    ));
	}

}