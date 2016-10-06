<?php

// src/AppBundle/Form/AlbumType.php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
// use Symfony\Component\Form\Extension\Core\Type\DateType;

use AppBundle\Repository\CollectionRepository;


class AlbumType extends AbstractType
{

    private $tokenStorage;
    private $translator;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $user = $this->tokenStorage->getToken()->getUser();
        if (!$user || !method_exists($user, 'getId')) {
            throw new \LogicException(
                'Authenticated user not found.'
            );
        }

        $builder
            ->add('name', TextType::class, Array('label'=>'album.label.name'))
            ->add('description', TextType::class, Array('label'=>'album.label.description'))
            ->add('public', CheckboxType::class, Array('label'=>'album.label.make_public', 'required'=>false))
            ->add('collection', EntityType::class, Array(
                    'class' => 'AppBundle:Collection',
                    'choice_label' => 'name',
                    'label'=>'album.label.collection',
                    'query_builder' => function (CollectionRepository $cr) use ($user){
                        return $cr->queryByOwner($user, $user);
                    },
                ))
        ;
    }


	public function configureOptions(OptionsResolver $resolver)
	{
	    $resolver->setDefaults(array(
	        'data_class' => 'AppBundle\Entity\Album',
	    ));
	}

}