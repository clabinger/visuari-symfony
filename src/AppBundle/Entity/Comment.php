<?php

// src/appBundle/Entity/Comment.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="comment")
 * @ORM\HasLifecycleCallbacks()
 */
class Comment
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Collection", inversedBy="comments")
     * @ORM\JoinColumn(name="collection_id", referencedColumnName="id")
     */    
    private $collection;
    
    /**
     * @ORM\ManyToOne(targetEntity="Album", inversedBy="comments")
     * @ORM\JoinColumn(name="album_id", referencedColumnName="id")
     */    
    private $album;
    
    /**
     * @ORM\ManyToOne(targetEntity="Album_Photo", inversedBy="comments")
     * @ORM\JoinColumn(name="album_photo_id", referencedColumnName="id")
     */    
    private $album_photo;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="comments")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", nullable=false)
     */    
    private $owner;
    
    
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
    private $content;

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