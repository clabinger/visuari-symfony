<?php

// src/appBundle/Entity/Album.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AlbumRepository")
 * @ORM\Table(name="album")
 * @ORM\HasLifecycleCallbacks()
 */
class Album
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
     * @ORM\ManyToOne(targetEntity="Collection", inversedBy="albums", cascade={"persist"})
     * @ORM\JoinColumn(name="collection_id", referencedColumnName="id", nullable=false)
     * @Assert\Type(type="AppBundle\Entity\Collection")
     * @Assert\Valid()
     */    
    private $collection;

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
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="albums")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", nullable=false)
     */    
    private $owner;

    /**
     * @ORM\OneToMany(targetEntity="Album_Photo", mappedBy="album")
     * @ORM\OrderBy({"album" = "ASC", "position" = "ASC"})
     */
    private $photos;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="album")
     */
    private $comments;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default" : false})
     * @Assert\Type("bool")
     */
    private $public;
    
    /**
     * @ORM\OneToMany(targetEntity="Permission", mappedBy="album")
     */
    private $permissions;


    public function __construct(){
        
        $this->photos = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->permissions = new ArrayCollection();

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
     * Get public
     *
     * @return bool
     */
    public function isPublic() { return $this->public; }

    /**
     * Set public
     *
     * @param bool $public
     *
     * @return Album
     */
    public function setPublic($public) { 

        $this->public = ($public ? true : false); 
        
        return $this;
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
     * Set name
     *
     * @param string $name
     *
     * @return Album
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     *
     * @return Album
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
     * @return Album
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
     * Set description
     *
     * @param string $description
     *
     * @return Album
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
     * Set collection
     *
     * @param \AppBundle\Entity\Collection $collection
     *
     * @return Album
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
     * Set owner
     *
     * @param \AppBundle\Entity\User $owner
     *
     * @return Album
     */
    public function setOwner(\AppBundle\Entity\User $owner = null)
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

    public function getPhotos() {
        return $this->photos;
    }

    public function removeAlbumPhoto(Album_Photo $album_photo)
    {
        $this->photos->removeElement($album_photo);
    }


    /**
     * Get public
     *
     * @return boolean
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * Add photo
     *
     * @param \AppBundle\Entity\Album_Photo $photo
     *
     * @return Album
     */
    public function addPhoto(\AppBundle\Entity\Album_Photo $photo)
    {
        $this->photos[] = $photo;

        return $this;
    }

    /**
     * Remove photo
     *
     * @param \AppBundle\Entity\Album_Photo $photo
     */
    public function removePhoto(\AppBundle\Entity\Album_Photo $photo)
    {
        $this->photos->removeElement($photo);
    }

    /**
     * Add comment
     *
     * @param \AppBundle\Entity\Comment $comment
     *
     * @return Album
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
     * Add permission
     *
     * @param \AppBundle\Entity\Permission $permission
     *
     * @return Album
     */
    public function addPermission(\AppBundle\Entity\Permission $permission)
    {

        $permission->setAlbum($this);

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
