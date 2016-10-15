<?php

// src/AppBundle/Form/AlbumPhotoType.php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

// use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

// use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
// use Symfony\Component\Form\Extension\Core\Type\DateType;

use AppBundle\Repository\CollectionRepository;


class AlbumPhotoType extends AbstractType
{

    private $tokenStorage;
    private $translator;

    // public function __construct(TokenStorageInterface $tokenStorage)
    // {
    //     $this->tokenStorage = $tokenStorage;
    // }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        // $user = $this->tokenStorage->getToken()->getUser();
        // if (!$user || !method_exists($user, 'getId')) {
        //     throw new \LogicException(
        //         'Authenticated user not found.'
        //     );
        // }

        $builder
            ->add('photo', EntityType::class, array('class'=>'AppBundle:Photo', 'choice_label'=>'size_thumb', 'disabled'=>true))
            ->add('title', TextType::class, array('label'=>'album_photo.label.title', 'required'=>false))
            ->add('caption', TextType::class, array('label'=>'album_photo.label.caption', 'required'=>false))
            ->add('description', TextType::class, array('label'=>'album_photo.label.description', 'required'=>false))
            ->add('position', HiddenType::class, array('attr'=>array('class'=>'reorder-position')))
        ;
    }


	public function configureOptions(OptionsResolver $resolver)
	{
	    $resolver->setDefaults(array(
	        'data_class' => 'AppBundle\Entity\Album_Photo',
	    ));
	}

}