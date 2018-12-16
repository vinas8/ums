<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
//IMMUTABLE
/**
 * @ORM\Entity(repositoryClass="App\Repository\ApiTokenRepository")
 */
class ApiToken
{

    public function __construct(User $user) {
        //kita naudot?
        $this->token = bin2hex(random_bytes(60));
        $this->user =$user;
        $this->expiresAt = new \DateTime("+1 hour");
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $token;

    /**
     * @ORM\Column(type="datetime")
     */
    private $expiresAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function getExpiresAt(): ?\DateTimeInterface
    {
        return $this->expiresAt;
    }

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="apiToken")
     */
    private $user;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function isExpired(): bool {
        return $this->getExpiresAt() <= new \DateTime();
    }
}
