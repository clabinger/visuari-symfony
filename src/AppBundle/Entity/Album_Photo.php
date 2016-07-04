<?php

// src/AppBindle/Entity/Album_Photo.php

namespace AppBundle\Entity;

class Album_Photo
{
    private $id;
    
    private $photo;

    private $album;

    private $position;

    private $title;
    
    private $caption;
    
    private $description;

    private $create_date;
    
    private $change_date;

    private $comments;

    public function __construct(){
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