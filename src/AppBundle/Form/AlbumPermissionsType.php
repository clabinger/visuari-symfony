<?php

// src/AppBundle/Form/AlbumPermissionsType.php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use AppBundle\Utils\CurrentUser;


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


    public function __construct(CurrentUser $current_user)
    {
        $this->current_user = $current_user->get();
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $user = $this->current_user;

        if (!$user) {
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
                'label' => false,
                'entry_options' => array(
                    'label'=> false,
                ),
            ))
        ;
    }


	public function configureOptions(OptionsResolver $resolver)
	{
	    $resolver->setDefaults(array(

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