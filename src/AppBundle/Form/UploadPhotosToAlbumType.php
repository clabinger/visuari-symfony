<?php

// src/AppBundle/Form/UploadPhotosToAlbumType.php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use AppBundle\Utils\CurrentUser;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
// use Symfony\Component\Form\Extension\Core\Type\DateType;

use AppBundle\Repository\CollectionRepository;


class UploadPhotosToAlbumType extends AbstractType
{

    private $translator;

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
            ->add('album', HiddenType::class, Array('data' => $builder->getData()->getId() , 'mapped' => false , 'attr' => array('class' => 'uploader_album_id') ))
        ;
    }


	public function configureOptions(OptionsResolver $resolver)
	{
	    $resolver->setDefaults(array(
	        'data_class' => 'AppBundle\Entity\Album',
	    ));
	}

}