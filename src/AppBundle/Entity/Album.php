<?php

// src/AppBindle/Entity/Album.php

namespace AppBundle\Entity;

class Album
{

    private $id;
    
    private $name;
    
    private $collection;

    private $create_date;
    
    private $change_date;
    
    private $description;

    private $owner;

    private $photos;

    private $comments;

    public function __construct(){
        
        $this->photos = new ArrayCollection();
        $this->comments = new ArrayCollection();

    }

    public function getName() { return $this->name; }

    public function setCreate_DateValue(){
        $this->create_date = new \DateTime();
    }

    public function setChange_DateValue(){
        $this->change_date = new \DateTime();
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
        $this->create_date = $createDate;

        return $this;
    }

    /**
     * Get createDate
     *
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->create_date;
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
        $this->change_date = $changeDate;

        return $this;
    }

    /**
     * Get changeDate
     *
     * @return \DateTime
     */
    public function getChangeDate()
    {
        return $this->change_date;
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
}
