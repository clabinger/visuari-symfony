<?php

// src/AppBindle/Entity/Collection.php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Collection
{
    private $id;
    
    private $name;
    
    private $create_date;
    
    private $change_date;
    
    private $description;

    private $owner;

    private $albums;

    private $comments;

    private $public;

    public function __construct(){
        $this->albums = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function setName($name) { $this->name = $name; }

    public function getName() { return $this->name; }
    
    public function setDescription($description) { $this->description = $description; }

    public function getDescription() { return $this->description; }

    public function getPublic() { return $this->public; }

    public function setPublic($public) { $this->public = ($public ? true : false); }

    public function setOwner($owner) { $this->owner = $owner; }

    public function getOwner() { return $this->owner; }

    public function setCreate_DateValue(){
        $this->create_date = new \DateTime();
    }

    public function setChange_DateValue(){
        $this->change_date = new \DateTime();
    }


}