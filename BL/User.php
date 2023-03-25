<?php

namespace BL;

use BL\_Interfaces\IUser;

class User implements IUser
{
    //region Properties
    private ?int $id;
    private string $username;
    private string $passwordHash;
    private string $email;
    private ?string $profilePicturePath;
    private bool $admin;
    private bool $muted;
    //endregion

    //region Constructors
    public function __construct(?int $id, string $username, string $passwordHash,
                                string $email, ?string $profilePicturePath, bool $admin, bool $muted)
    {
        $this->id = $id;
        $this->username = $username;
        $this->passwordHash = $passwordHash;
        $this->email = $email;
        $this->profilePicturePath = $profilePicturePath;
        $this->admin = $admin;
        $this->muted = $muted;

        // TODO: validate values
    }

    public static function createNewUser(string $username, string $passwordHash,
                                         string $email): IUser
    {
        return new self(null, $username, $passwordHash, $email, null,
            false, false);
    }
    //endregion

    //region Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getProfilePicturePath(): ?string
    {
        return $this->profilePicturePath;
    }

    public function isAdmin(): bool
    {
        return $this->admin;
    }

    public function isMuted(): bool
    {
        return $this->muted;
    }
    //endregion

    //region Setters
    public function setUsername(string $username): IUser
    {
        // TODO: validation
        $this->username = $username;
        return $this;
    }

    public function setPasswordHash(string $passwordHash): IUser
    {
        // TODO: validation
        $this->passwordHash = $passwordHash;
        return $this;
    }

    public function setEmail(string $email): IUser
    {
        // TODO: validation
        $this->email = $email;
        return $this;
    }

    public function setProfilePicturePath(?string $profilePicturePath): IUser
    {
        // TODO: if string is empty set value to null
        $this->profilePicturePath = $profilePicturePath;
        return $this;
    }

    public function setAdmin(bool $isAdmin): IUser
    {
        $this->admin = $isAdmin;
        return $this;
    }

    public function setMuted(bool $isMuted): IUser
    {
        $this->muted = $isMuted;
        return $this;
    }
    //endregion
}