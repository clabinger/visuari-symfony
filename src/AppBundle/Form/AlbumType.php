<?php

// src/AppBundle/Form/AlbumType.php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityManager;
use AppBundle\Utils\CurrentUser;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints\NotBlank;


use AppBundle\Repository\CollectionRepository;


class AlbumType extends AbstractType
{

    private $translator;

    public function __construct(CurrentUser $current_user, RequestStack $requestStack, EntityManager $em)
    {
        $this->current_user = $current_user->get();
        $this->request = $requestStack;
        $this->em = $em;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $user = $this->current_user;
        if (!$user) {
            throw new \LogicException(
                'Authenticated user not found.'
            );
        }

        // If we are creating a new album, this will be the collection the album is going in (if it is set as a parameter in the URL)
        $request_collection_id = $this->request->getCurrentRequest()->get('collection');

        $default = $request_collection_id
            ? $this->em->getReference('AppBundle:Collection', $request_collection_id) // 
            : $builder->getData()->getCollection(); // Otherwise use the default Form functionality

        $builder
            ->add('name', TextType::class, Array('label'=>'album.label.name'))
            ->add('description', TextType::class, Array('label'=>'album.label.description', 'required'=>false))
            ->add('collection', EntityType::class, Array(
                    'class' => 'AppBundle:Collection',
                    'choice_label' => 'name',
                    'label'=>'album.label.collection',
                    'query_builder' => function (CollectionRepository $cr) use ($user){
                        return $cr->queryByOwner($user, $user);
                    },
                    'data'=> $default,
                    'placeholder' => 'album.button.choose_collection',
                    'constraints'=>array(
                        new NotBlank(array(
                            'message'=>'album.collection.required'
                        )),
                    )
                ))
            ->add('public', CheckboxType::class, Array('label'=>'album.label.make_public', 'required'=>false))
            ->add('photos', CollectionType::class, array(
                'entry_type' => AlbumPhotoType::class,
                'allow_delete' => true,
                'label' => false,
                'entry_options' => array(
                    'label'=> false,
                ),
                'attr'=>array(
                    'class'=>'reorder-photos',
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

}