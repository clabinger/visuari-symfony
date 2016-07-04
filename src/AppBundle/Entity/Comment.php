<?php

// src/AppBindle/Entity/Comment.php

namespace AppBundle\Entity;

class Comment
{
    private $id;
    
    private $collection;
    
    private $album;
    
    private $album_photo;
    
    private $owner;
    
    private $create_date;

    private $change_date;

    private $content;


    public function setCreate_DateValue(){
        $this->create_date = new \DateTime();
    }

    public function setChange_DateValue(){
        $this->change_date = new \DateTime();
    }

}