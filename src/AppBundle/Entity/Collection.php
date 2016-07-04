<?php

// src/AppBindle/Entity/Collection.php

namespace AppBundle\Entity;

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

    public function __construct(){
        $this->albums = new ArrayCollection();
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