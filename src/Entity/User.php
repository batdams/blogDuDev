<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')] // typage plus nécessaire grace aux attributs
    private int $id;
    #[ORM\Column(type: 'string')]
    private string $firstname;
    #[ORM\Column(type: 'string')]
    private string $lastname;
    #[ORM\Column(type: 'string')]
    private string $username;
    #[ORM\Column(type: 'string')]
    private string $password;
    #[ORM\Column(type: 'json')] // type json pour mysql (un array est un json en base)
    private array $roles = [];

    /**
     * @var Collection<int, ArticleNote>
     */
    #[ORM\OneToMany(targetEntity: ArticleNote::class, mappedBy: 'User', orphanRemoval: true)]
    private Collection $articleNotes;

    public function __construct()
    {
        $this->articleNotes = new ArrayCollection();
    }

    // ----- GETTERS -----
    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored in a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return string[]
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee everey user at least has ROLE_USER
        $roles[] =  'ROLE_USER';
        return $roles;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials(): void
    {
        return;
    }

    /**
     * Returns the identifier for this user (e.g. username or email address).
     */
    public function getUserIdentifier(): string
    {
        return $this->username;
    }

     /**
     * Returns the hashed password used to authenticate the user.
     *
     * Usually on authentication, a plain-text password will be compared to this value.
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of firstname
     */ 
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Get the value of lastname
     */ 
    public function getLastname()
    {
        return $this->lastname;
    }

    // ----- SETTERS -----
    

    /**
     * Set the value of firstname
     *
     * @return  self
     */ 
    public function setFirstname($firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Set the value of lastname
     *
     * @return  self
     */ 
    public function setLastname($lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Set the value of username
     *
     * @return  self
     */ 
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set the value of roles
     *
     * @return  self
     */ 
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection<int, ArticleNote>
     */
    public function getArticleNotes(): Collection
    {
        return $this->articleNotes;
    }

    public function addArticleNote(ArticleNote $articleNote): static
    {
        if (!$this->articleNotes->contains($articleNote)) {
            $this->articleNotes->add($articleNote);
            $articleNote->setUser($this);
        }

        return $this;
    }

    public function removeArticleNote(ArticleNote $articleNote): static
    {
        if ($this->articleNotes->removeElement($articleNote)) {
            // set the owning side to null (unless already changed)
            if ($articleNote->getUser() === $this) {
                $articleNote->setUser(null);
            }
        }

        return $this;
    }
}