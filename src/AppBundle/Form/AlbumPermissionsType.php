<?php

// src/AppBundle/Form/AlbumPermissionsType.php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
// use Symfony\Component\Form\Extension\Core\Type\DateType;

use AppBundle\Repository\CollectionRepository;
use AppBundle\Entity\Permission;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class AlbumPermissionsType extends AbstractType
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
            ->add('permissions', CollectionType::class, array(
                'entry_type' => PermissionType::class,
                'prototype' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'album.label.permissions',
                'entry_options' => array(
                    'label'=> false,
                ),
            ))
        ;
    }


	public function configureOptions(OptionsResolver $resolver)
	{
	    $resolver->setDefaults(array(
	        'data_class' => 'AppBundle\Entity\Album',
	    ));
	}

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        @usort($view['permissions']->children, function (FormView $a, FormView $b) {
            
            // 'name' contains the numerical index from the DOM field naming
            return ($a->vars['name'] < $b->vars['name']) ? 1 : -1;
        });
    }

}