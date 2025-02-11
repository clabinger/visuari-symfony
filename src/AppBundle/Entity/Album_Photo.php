<?php

// src/appBundle/Entity/Album_Photo.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Album_PhotoRepository")
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
     * @ORM\Column(type="integer")
     */
    private $uploadPosition; // used to keep track of the order in which files were dropped into the uploader (may be different from the order in which the server received them). Will be cleared when an upload is finished



    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $caption;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createDate;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $changeDate;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $notes;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="album_photo")
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="Permission", mappedBy="album_photo", cascade={"persist"})
     */
    private $permissions;


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
     * Set position
     *
     * @param integer $position
     *
     * @return Album_Photo
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Album_Photo
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set caption
     *
     * @param string $caption
     *
     * @return Album_Photo
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;

        return $this;
    }

    /**
     * Get caption
     *
     * @return string
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Album_Photo
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     *
     * @return Album_Photo
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
     * @return Album_Photo
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
     * Set notes
     *
     * @param string $notes
     *
     * @return Album_Photo
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Get notes
     *
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set photo
     *
     * @param \AppBundle\Entity\Photo $photo
     *
     * @return Album_Photo
     */
    public function setPhoto(\AppBundle\Entity\Photo $photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return \AppBundle\Entity\Photo
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set album
     *
     * @param \AppBundle\Entity\Album $album
     *
     * @return Album_Photo
     */
    public function setAlbum(\AppBundle\Entity\Album $album)
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
     * Add comment
     *
     * @param \AppBundle\Entity\Comment $comment
     *
     * @return Album_Photo
     */
    public function addComment(\AppBundle\Entity\Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \AppBundle\Entity\Comment $comment
     */
    public function removeComment(\AppBundle\Entity\Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set uploadPosition
     *
     * @param integer $uploadPosition
     *
     * @return Album_Photo
     */
    public function setUploadPosition($uploadPosition)
    {
        $this->uploadPosition = $uploadPosition;

        return $this;
    }

    /**
     * Get uploadPosition
     *
     * @return integer
     */
    public function getUploadPosition()
    {
        return $this->uploadPosition;
    }

    /**
     * Add permission
     *
     * @param \AppBundle\Entity\Permission $permission
     *
     * @return Album_Photo
     */
    public function addPermission(\AppBundle\Entity\Permission $permission)
    {
        $this->permissions[] = $permission;

        return $this;
    }

    /**
     * Remove permission
     *
     * @param \AppBundle\Entity\Permission $permission
     */
    public function removePermission(\AppBundle\Entity\Permission $permission)
    {
        $this->permissions->removeElement($permission);
    }

    /**
     * Get permissions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPermissions()
    {
        return $this->permissions;
    }
}
