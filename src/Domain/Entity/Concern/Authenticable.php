<?php

namespace AppoloDev\SFToolboxBundle\Domain\Entity\Concern;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait Authenticable
{
    #[ORM\Column(type: Types::STRING, length: 180, unique: true)]
    #[Groups(['authentication'])]
    private string $email;

    #[ORM\Column(type: Types::JSON)]
    #[Groups(['authentication'])]
    private array $roles = [];

    #[ORM\Column(type: Types::STRING)]
    private string $password;

    private ?string $plainPassword = '';

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $confirmationTokenExpiredAt;

    #[ORM\Column(type: Types::STRING, length: 100, nullable: true)]
    private ?string $confirmationToken = null;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getUsername(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->getRoles());
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    public function setConfirmationToken(?string $confirmationToken): self
    {
        $this->confirmationToken = $confirmationToken;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getConfirmationTokenExpiredAt(): ?\DateTimeInterface
    {
        return $this->confirmationTokenExpiredAt;
    }

    public function setConfirmationTokenExpiredAt(?\DateTimeInterface $confirmationTokenExpiredAt): self
    {
        $this->confirmationTokenExpiredAt = $confirmationTokenExpiredAt;

        return $this;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
