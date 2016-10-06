<?php

// src/appBundle/Entity/Album_Photo.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="album_photo")
 * @ORM\HasLifecycleCallbacks()
 */
class Album_Photo
{
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Photo")
     * @ORM\JoinColumn(name="photo_id", referencedColumnName="id", nullable=false)
     */    
    private $photo;

    /**
     * @ORM\ManyToOne(targetEntity="Album", inversedBy="photos")
     * @ORM\JoinColumn(name="album_id", referencedColumnName="id", nullable=false)
     */    
    private $album;

    /**
     * @ORM\Column(type="integer")
     */
    private $position;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;
    
    /**
     * @ORM\Column(type="text")
     */
    private $caption;
    
    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createDate;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $changeDate;

    /**
     * @ORM\Column(type="text")
     */
    private $notes;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="album_photo")
     */
    private $comments;

    public function __construct(){
        $this->comments = new ArrayCollection();
    }

    public function getName() { return $this->name; }

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


}