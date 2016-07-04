<?php

// src/AppBindle/Entity/Star.php

namespace AppBundle\Entity;

class Star
{
    private $id;
    
    private $owner;
    
    private $create_date;
    
    private $album_photo;

    public function setCreate_DateValue(){
        $this->create_date = new \DateTime();
    }

}