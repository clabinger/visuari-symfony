<?php

// src/AppBundle/Form/PermissionType.php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityManager;

// use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

// use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
// use Symfony\Component\Form\Extension\Core\Type\DateType;

use AppBundle\Repository\CollectionRepository;
use AppBundle\Form\DataTransformer\UserToUsernameTransformer;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


class PermissionType extends AbstractType
{

    private $entityManager;

    public function __construct(EntityManager $em)
    {
        $this->entityManager = $em;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('grantee', TextType::class, array(
                'label'=>'permission.label.grantee', 
                'invalid_message'=>'permission.grantee.invalid',
            ))
            ->add('level', ChoiceType::class, array(
                'label'=>'permission.label.level',
                'choices'=>array(
                    'permission.level.0'=>0,
                    'permission.level.1'=>1,
                    'permission.level.2'=>2,
                    'permission.level.3'=>3,
                )
            ))
        ;


        $builder->get('grantee')
            ->addModelTransformer(new UserToUsernameTransformer($this->entityManager));

    }


	public function configureOptions(OptionsResolver $resolver)
	{
	    $resolver->setDefaults(array(
	        'data_class' => 'AppBundle\Entity\Permission',
            'constraints'=>array(
                new UniqueEntity(array(
                    'fields'=>['grantee','album'],
                    'message'=>'permission.grantee.duplicate'
                )),
            ),
	    ));
	}

}