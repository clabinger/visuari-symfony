<?php

// src/appBundle/Entity/Permission.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="permission")
 */
class Permission
{

	/**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Collection")
     * @ORM\JoinColumn(name="collection_id", referencedColumnName="id")
     */    
    private $collection;
    
    /**
     * @ORM\ManyToOne(targetEntity="Album")
     * @ORM\JoinColumn(name="album_id", referencedColumnName="id")
     */    
    private $album;
    
    /**
     * @ORM\ManyToOne(targetEntity="Album_Photo")
     * @ORM\JoinColumn(name="album_photo_id", referencedColumnName="id")
     */    
    private $album_photo;
    
    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="grantee_id", referencedColumnName="id", nullable=false)
     */    
    private $grantee;
    
    /**
     * @ORM\Column(type="integer", nullable=false, options={"default" : 0})
     */
    private $level;

    
}