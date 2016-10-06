<?php

// src/AppBundle/Entity/Collection.php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CollectionRepository")
 * @ORM\Table(name="collection")
 * @ORM\HasLifecycleCallbacks()
 */
class Collection
{

    const NUM_ITEMS = 10;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $createDate;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $changeDate;
    
    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="collections")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", nullable=false)
     */    
    private $owner;

    /**
     * @ORM\OneToMany(targetEntity="Album", mappedBy="collection")
     */        
    private $albums;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="collection")
     */    
    private $comments;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default" : false})
     * @Assert\Type("bool")
     */
    private $public;


    public function __construct(){
        $this->albums = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId() { return $this->id; }

    public function setName($name) { $this->name = $name; }

    public function getName() { return $this->name; }
    
    public function setDescription($description) { $this->description = $description; }

    public function getDescription() { return $this->description; }

    public function isPublic() { return $this->public; }

    public function setPublic($public) { $this->public = ($public ? true : false); }

    public function setOwner($owner) { $this->owner = $owner; }

    public function getOwner() { return $this->owner; }

    public function isOwner($user) { return $user->getId() === $this->getOwner()->getId(); }

    /**
     * @ORM\PrePersist
     */
    public function setCreateDateValue(){
        $this->createDate = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function setChangeDateValue(){
        $this->changeDate = new \DateTime();
    }

    public function getAlbums() {
        return $this->albums;
    }


}