<?php

// src/appBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @ORM\AttributeOverrides({
 *      @ORM\AttributeOverride(name="usernameCanonical",
 *          column=@ORM\Column(
 *              name     = "username_canonical",
 *              unique   = true,
 *              length   = 191
 *          )
 *      ),
 *      @ORM\AttributeOverride(name="emailCanonical",
 *          column=@ORM\Column(
 *              name     = "email_canonical",
 *              unique   = true,
 *              length   = 191
 *          )
 *      )
 * })
 */
class User extends BaseUser {

	/**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
	protected $id;
 
 	/**
     * @ORM\OneToMany(targetEntity="Collection", mappedBy="owner")
     */
	private $collections;

	/**
     * @ORM\OneToMany(targetEntity="Album", mappedBy="owner")
     */
	private $albums;

	/**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="owner")
     */
    private $comments;

	public function __construct(){
		parent::__construct();

		$this->collections = new ArrayCollection();

	}

	public function getCollections(){
        return $this->collections;
	}




}
