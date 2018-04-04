<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\Length(min=3, max=50)
     * @ORM\Column(type="string", length=100, unique=true)
     */
    private $title;

    /**
     * @Assert\Length(min=15)
     * @ORM\Column(type="text")
     */
    private $description;
    
    /**
     * @ORM\Column(type="string", length=255 )
     * Assert\NotBlank(groups={"insertion"})
     * @Assert\Image(maxSize="2M", minWidth="200", minHeight ="300")
     * @var object
     */
    private $image;

     /**
     * @ORM\ManyToMany(targetEntity="Tag", invertedBy="products")
     * @var Collection
     */
    private $tags;
    
    public function __construct() {
        $this->tags = new ArrayCollection();
    }
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="products")
     * @var User owner
     */ 
    private $owner;
    
    
    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
    
    public function getOwner(): User {
        return $this->owner;
    }

    public function setOwner(User $owner) {
        $this->owner = $owner;
        return $this;
    }
    
    public function getImage() {
        return $this->image;
    }

    public function setImage($image) {
        $this->image = $image;
        return $this;
    }

    public function getTags(): Collection {
        return $this->tags;
    }

}
