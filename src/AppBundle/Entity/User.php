<?php

// src/AppBindle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

class User extends BaseUser {

	protected $id;

	private $collections;

	public function __construct(){
		parent::__construct();

		$this->collections = new ArrayCollection();

	}

	public function getCollections(){
        return $this->collections;
	}




}
