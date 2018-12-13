<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $apiToken;


    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

//    /**
//     * @ORM\ManyToMany(targetEntity="App\Entity\Group", mappedBy="users")
//     */
//    private $groups;

    public function __construct()
    {
//        $this->groups = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }


    //- As an admin I can add users. A user has a name.
    public function adminAddUser() {

    }
    //- As an admin I can delete users.
    public function adminDeleteUser() {

    }


    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getApiToken(): ?string {
        return $this->apiToken;
    }

    /**
     * @param mixed $apiToken
     */
    public function setApiToken($apiToken): void {
        $this->apiToken = $apiToken;
    }

//    /**
//     * @return Collection|Group[]
//     */
//    public function getGroups(): Collection
//    {
//        return $this->groups;
//    }


    //- As an admin I can delete groups when they no longer have members.

    //- As an admin I can assign users to a group they aren’t already part of.
//    public function addToGroup(Group $group): self
//    {
//        if (!$this->groups->contains($group)) {
//            $this->groups[] = $group;
//            $group->addUser($this);
//        }
//
//        return $this;
//    }
//
//    //- As an admin I can remove users from a group.
//    public function removeFromGroup(Group $group): self
//    {
//        if ($this->groups->contains($group)) {
//            $this->groups->removeElement($group);
//            $group->removeUser($this);
//        }
//
//        return $this;
//    }
}

