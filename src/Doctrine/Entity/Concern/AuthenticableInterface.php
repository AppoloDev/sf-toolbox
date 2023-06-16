<?php

namespace AppoloDev\SFToolboxBundle\Doctrine\Entity\Concern;

interface AuthenticableInterface
{
    public function getEmail(): string;

    public function setEmail(string $email): self;

    public function getUserIdentifier(): string;

    public function getUsername(): string;

    public function getRoles(): array;

    public function setRoles(array $roles): self;

    public function hasRole(string $role): bool;

    public function getPassword(): string;

    public function setPassword(string $password): self;

    public function getConfirmationToken(): ?string;

    public function setConfirmationToken(?string $confirmationToken): self;

    public function getPlainPassword(): ?string;

    public function setPlainPassword(?string $plainPassword): self;

    public function getConfirmationTokenExpiredAt(): ?\DateTimeInterface;

    public function setConfirmationTokenExpiredAt(?\DateTimeInterface $confirmationTokenExpiredAt): self;

    public function getSalt(): ?string;

    public function eraseCredentials(): void;
}
