<?php

// src/appBundle/Entity/Comment.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Utils\CurrentUser;

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
     * Set createDate
     *
     * @param \DateTime $createDate
     *
     * @return Comment
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
     * @return Comment
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
     * Set content
     *
     * @param string $content
     *
     * @return Comment
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set collection
     *
     * @param \AppBundle\Entity\Collection $collection
     *
     * @return Comment
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
     * @return Comment
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
     * @return Comment
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
     * Set owner
     *
     * @param \AppBundle\Entity\User $owner
     *
     * @return Comment
     */
    public function setOwner(\AppBundle\Entity\User $owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \AppBundle\Entity\User
     */
    public function getOwner()
    {
        return $this->owner;
    }
}
