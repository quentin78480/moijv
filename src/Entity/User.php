<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @UniqueEntity("email")
 * @UniqueEntity("username")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\Length(min=2, max=50)
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private $username;

    /**
     * @Assert\Length(min=5, max=50)
     * @ORM\Column(type="string", length=255)

     */
    private $password;

    /**
     * @Assert\Email()
     * @ORM\Column(type="string", length=100, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="datetime")
     */
    private $registerDate;
    
    /**
     * 
     * @ORM\Column(type="string", length=100)
     */ 
    private $roles;
    
     /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="owner")
     * @var Collection products
     */
    private $products;
    
    /**
     * @ORM\OneToMany(targetEntity="Loan", mappedBy="loaner")
     * @var Collection 
     */
    private $loans;
    
    public function __construct() {
        $this->products = new ArrayCollection();
        $this->loans = new ArrayCollection();
    }
    
    function getProducts(): Collection {
        return $this->products;
    }

    function setRoles($roles) {
        $this->roles = $roles;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRegisterDate(): \DateTimeInterface
    {
        return $this->registerDate;
    }

    public function setRegisterDate(\DateTimeInterface $registerDate): self
    {
        $this->registerDate = $registerDate;

        return $this;
    }

    public function eraseCredentials() {
        
    }

    public function getRoles() {
        return explode('|', $this->roles);
    }

    public function getSalt() {
        return null;
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized);
    }
    
    public function getLoans(): Collection {
        return $this->loans;
    }

    public function setLoans(Collection $loans) {
        $this->loans = $loans;
        return $this;
    }


}
