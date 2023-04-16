<?php

namespace BL\DTO;

use BL\_enums\EListVisibility;
use BL\DTO\_Interfaces\IUser;
use Exception;

class User implements IUser
{
    //region Properties
    private ?int $id;
    private string $username;
    private string $passwordHash;
    private string $email;
    private ?string $profilePicturePath;
    private ?string $timestampOfRegistration;
    private bool $admin;
    private bool $canComment;
    private EListVisibility $listVisibility;
    //endregion

    //region Constructors
    public function __construct(?int    $id, string $username, string $passwordHash, string $email,
                                ?string $profilePicturePath, ?string $timestampOfRegistration, bool $admin, bool $canComment, EListVisibility $listVisibility)
    {
        $this->id = $id;
        $this->username = $username;
        $this->passwordHash = $passwordHash;
        $this->email = $email;
        $this->profilePicturePath = $profilePicturePath;
        $this->timestampOfRegistration = $timestampOfRegistration;
        $this->admin = $admin;
        $this->canComment = $canComment;
        $this->listVisibility = $listVisibility;
    }

    /**
     * @inheritDoc
     */
    public static function createNewUser(string $username, string $passwordHash,
                                         string $email, bool $admin=false): IUser
    {
        if (empty(trim($username))) throw new Exception('Username is empty', 21);
        if (empty(trim($passwordHash))) throw new Exception('Password hash is empty', 22);
        if (empty(trim($email)) || !preg_match("/^[\w-\.]+@([\w-]+\.)+[\w-]{2,6}$/", $email)) throw new Exception('Invalid e-mail address', 23);

        return new self(null, $username, $passwordHash, $email, null, null,
            $admin, false, EListVisibility::Public);
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

    public function getTimestampOfRegistration(): string
    {
        return $this->timestampOfRegistration;
    }

    public function isAdmin(): bool
    {
        return $this->admin;
    }

    public function canComment(): bool
    {
        return $this->canComment;
    }
    public function getListVisibility(): EListVisibility
    {
        return $this->listVisibility;
    }
    //endregion

    //region Setters
    /**
     * @inheritDoc
     */
    public function setUsername(string $username): IUser
    {
        if (empty(trim($username))) throw new Exception('Username is empty', 21);
        $this->username = $username;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setPasswordHash(string $passwordHash): IUser
    {
        if (empty(trim($passwordHash))) throw new Exception('Password hash is empty', 22);
        $this->passwordHash = $passwordHash;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setEmail(string $email): IUser
    {
        if (empty(trim($email)) || !preg_match("/^[\w-\.]+@([\w-]+\.)+[\w-]{2,6}$/", $email)) throw new Exception('Invalid e-mail address', 23);
        $this->email = $email;
        return $this;
    }

    public function setProfilePicturePath(?string $profilePicturePath): IUser
    {
        if (empty(trim($profilePicturePath))) $profilePicturePath = null;
        $this->profilePicturePath = $profilePicturePath;
        return $this;
    }

    public function setAdmin(bool $isAdmin): IUser
    {
        $this->admin = $isAdmin;
        return $this;
    }

    public function setCanComment(bool $canComment): IUser
    {
        $this->canComment = $canComment;
        return $this;
    }

    public function setListVisibility(EListVisibility $listVisibility): IUser
    {
        $this->listVisibility = $listVisibility;
        return $this;
    }
    //endregion
}