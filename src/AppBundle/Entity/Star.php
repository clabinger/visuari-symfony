<?php

// src/appBundle/Entity/Star.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="star")
 * @ORM\HasLifecycleCallbacks()
 */
class Star
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="stars")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", nullable=false)
     */    
    private $owner;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $createDate;
    
    /**
     * @ORM\ManyToOne(targetEntity="Album_Photo", inversedBy="starredBy")
     * @ORM\JoinColumn(name="album_photo_id", referencedColumnName="id", nullable=false)
     */    
    private $album_photo;

	/**
     * @ORM\PrePersist
     */
    public function setCreateDateValue(){
        $this->createDate = new \DateTime();
    }

}