<?php

// src/appBundle/Entity/Permission.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="permission",uniqueConstraints={
 *     @ORM\UniqueConstraint(name="permission_collection_unique", columns={"grantee_id", "collection_id"}),
 *     @ORM\UniqueConstraint(name="permission_album_unique", columns={"grantee_id", "album_id"}),
 *     @ORM\UniqueConstraint(name="permission_album_photo_unique", columns={"grantee_id", "album_photo_id"})
 * })
 * @ORM\HasLifecycleCallbacks()
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
     * @ORM\ManyToOne(targetEntity="Collection", inversedBy="permissions")
     * @ORM\JoinColumn(name="collection_id", referencedColumnName="id")
     */    
    private $collection;
    
    /**
     * @ORM\ManyToOne(targetEntity="Album", inversedBy="permissions")
     * @ORM\JoinColumn(name="album_id", referencedColumnName="id")
     */    
    private $album;
    
    /**
     * @ORM\ManyToOne(targetEntity="Album_Photo", inversedBy="permissions")
     * @ORM\JoinColumn(name="album_photo_id", referencedColumnName="id")
     */    
    private $album_photo;
    

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="granted_by_id", referencedColumnName="id", nullable=false)
     */    
    private $grantedBy;
    
    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="grantee_id", referencedColumnName="id", nullable=false)
     */    
    private $grantee;
    
    /**
     * @ORM\Column(type="integer", nullable=false, options={"default" : 0})
     */
    private $level;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createDate;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $changeDate;
    
    
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


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set level
     *
     * @param integer $level
     *
     * @return Permission
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return integer
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set collection
     *
     * @param \AppBundle\Entity\Collection $collection
     *
     * @return Permission
     */
    public function setCollection(\AppBundle\Entity\Collection $collection = null)
    {
        $this->collection = $collection;

        return $this;
    }

    /**
     * Get collection
     *
     * @return \AppBundle\Entity\Collection
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * Set album
     *
     * @param \AppBundle\Entity\Album $album
     *
     * @return Permission
     */
    public function setAlbum(\AppBundle\Entity\Album $album = null)
    {
        $this->album = $album;

        return $this;
    }

    /**
     * Get album
     *
     * @return \AppBundle\Entity\Album
     */
    public function getAlbum()
    {
        return $this->album;
    }

    /**
     * Set albumPhoto
     *
     * @param \AppBundle\Entity\Album_Photo $albumPhoto
     *
     * @return Permission
     */
    public function setAlbumPhoto(\AppBundle\Entity\Album_Photo $albumPhoto = null)
    {
        $this->album_photo = $albumPhoto;

        return $this;
    }

    /**
     * Get albumPhoto
     *
     * @return \AppBundle\Entity\Album_Photo
     */
    public function getAlbumPhoto()
    {
        return $this->album_photo;
    }

    /**
     * Set grantee
     *
     * @param \AppBundle\Entity\User $grantee
     *
     * @return Permission
     */
    public function setGrantee(\AppBundle\Entity\User $grantee)
    {
        $this->grantee = $grantee;

        return $this;
    }

    /**
     * Get grantee
     *
     * @return \AppBundle\Entity\User
     */
    public function getGrantee()
    {
        return $this->grantee;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     *
     * @return Permission
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Get createDate
     *
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * Set changeDate
     *
     * @param \DateTime $changeDate
     *
     * @return Permission
     */
    public function setChangeDate($changeDate)
    {
        $this->changeDate = $changeDate;

        return $this;
    }

    /**
     * Get changeDate
     *
     * @return \DateTime
     */
    public function getChangeDate()
    {
        return $this->changeDate;
    }

    /**
     * Set grantedBy
     *
     * @param \AppBundle\Entity\User $grantedBy
     *
     * @return Permission
     */
    public function setGrantedBy(\AppBundle\Entity\User $grantedBy)
    {
        $this->grantedBy = $grantedBy;

        return $this;
    }

    /**
     * Get grantedBy
     *
     * @return \AppBundle\Entity\User
     */
    public function getGrantedBy()
    {
        return $this->grantedBy;
    }
}
