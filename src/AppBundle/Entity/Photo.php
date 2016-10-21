<?php

// src/appBundle/Entity/Photo.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="photo")
 * @ORM\HasLifecycleCallbacks()
 */
class Photo
{

	/**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $size_original;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $size_medium;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $size_thumb;


    /**
     * @ORM\Column(type="smallint")
     */
    private $original_width;
    
    /**
     * @ORM\Column(type="smallint")
     */
    private $original_height;
    
    /**
     * @ORM\Column(type="smallint")
     */
    private $medium_width;
    
    /**
     * @ORM\Column(type="smallint")
     */
    private $medium_height;
    
    /**
     * @ORM\Column(type="smallint")
     */
    private $thumb_width;
    
    /**
     * @ORM\Column(type="smallint")
     */
    private $thumb_height;



    /**
     * @ORM\Column(type="datetime")
     */
    private $createDate;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $originalModified;
    

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $originalFilename;


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
     * Set sizeOriginal
     *
     * @param string $sizeOriginal
     *
     * @return Photo
     */
    public function setSizeOriginal($sizeOriginal)
    {
        $this->size_original = $sizeOriginal;

        return $this;
    }

    /**
     * Get sizeOriginal
     *
     * @return string
     */
    public function getSizeOriginal()
    {
        return $this->size_original;
    }

    /**
     * Set sizeMedium
     *
     * @param string $sizeMedium
     *
     * @return Photo
     */
    public function setSizeMedium($sizeMedium)
    {
        $this->size_medium = $sizeMedium;

        return $this;
    }

    /**
     * Get sizeMedium
     *
     * @return string
     */
    public function getSizeMedium()
    {
        return $this->size_medium;
    }

    /**
     * Set sizeThumb
     *
     * @param string $sizeThumb
     *
     * @return Photo
     */
    public function setSizeThumb($sizeThumb)
    {
        $this->size_thumb = $sizeThumb;

        return $this;
    }

    /**
     * Get sizeThumb
     *
     * @return string
     */
    public function getSizeThumb()
    {
        return $this->size_thumb;
    }


    /**
     * @ORM\PrePersist
     */
    public function setCreateDateValue(){
        $this->createDate = new \DateTime();
    }

    


    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     *
     * @return Photo
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
     * Set originalFilename
     *
     * @param string $originalFilename
     *
     * @return Photo
     */
    public function setOriginalFilename($originalFilename)
    {
        $this->originalFilename = $originalFilename;

        return $this;
    }

    /**
     * Get originalFilename
     *
     * @return string
     */
    public function getOriginalFilename()
    {
        return $this->originalFilename;
    }

    /**
     * Set originalModified
     *
     * @param \DateTime $originalModified
     *
     * @return Photo
     */
    public function setOriginalModified($originalModified)
    {
        $this->originalModified = $originalModified;

        return $this;
    }

    /**
     * Get originalModified
     *
     * @return \DateTime
     */
    public function getOriginalModified()
    {
        return $this->originalModified;
    }

    /**
     * Set originalWidth
     *
     * @param integer $originalWidth
     *
     * @return Photo
     */
    public function setOriginalWidth($originalWidth)
    {
        $this->original_width = $originalWidth;

        return $this;
    }

    /**
     * Get originalWidth
     *
     * @return integer
     */
    public function getOriginalWidth()
    {
        return $this->original_width;
    }

    /**
     * Set originalHeight
     *
     * @param integer $originalHeight
     *
     * @return Photo
     */
    public function setOriginalHeight($originalHeight)
    {
        $this->original_height = $originalHeight;

        return $this;
    }

    /**
     * Get originalHeight
     *
     * @return integer
     */
    public function getOriginalHeight()
    {
        return $this->original_height;
    }

    /**
     * Set mediumWidth
     *
     * @param integer $mediumWidth
     *
     * @return Photo
     */
    public function setMediumWidth($mediumWidth)
    {
        $this->medium_width = $mediumWidth;

        return $this;
    }

    /**
     * Get mediumWidth
     *
     * @return integer
     */
    public function getMediumWidth()
    {
        return $this->medium_width;
    }

    /**
     * Set mediumHeight
     *
     * @param integer $mediumHeight
     *
     * @return Photo
     */
    public function setMediumHeight($mediumHeight)
    {
        $this->medium_height = $mediumHeight;

        return $this;
    }

    /**
     * Get mediumHeight
     *
     * @return integer
     */
    public function getMediumHeight()
    {
        return $this->medium_height;
    }

    /**
     * Set thumbWidth
     *
     * @param integer $thumbWidth
     *
     * @return Photo
     */
    public function setThumbWidth($thumbWidth)
    {
        $this->thumb_width = $thumbWidth;

        return $this;
    }

    /**
     * Get thumbWidth
     *
     * @return integer
     */
    public function getThumbWidth()
    {
        return $this->thumb_width;
    }

    /**
     * Set thumbHeight
     *
     * @param integer $thumbHeight
     *
     * @return Photo
     */
    public function setThumbHeight($thumbHeight)
    {
        $this->thumb_height = $thumbHeight;

        return $this;
    }

    /**
     * Get thumbHeight
     *
     * @return integer
     */
    public function getThumbHeight()
    {
        return $this->thumb_height;
    }
}
