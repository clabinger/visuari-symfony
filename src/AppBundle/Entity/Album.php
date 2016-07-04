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

}