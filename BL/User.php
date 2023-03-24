<?php

namespace BL;

use BL\_Interfaces\IUser;
use BL\DataSource\_Interfaces\IDataSource;
use Exception;
use InvalidArgumentException;

class User implements IUser
{
    //region Properties
    private IDataSource $dataSource;
    private ?int $id;
    private string $username;
    private string $passwordHash;
    private string $email;
    private ?string $profilePicturePath;
    private bool $admin;
    private bool $muted;
    private array $friends;
    private array $shows;
    //endregion

    //region Constructors
    public function __construct(IDataSource $dataSource, ?int $id, string $username, string $passwordHash,
                                string $email, ?string $profilePicturePath, bool $admin, bool $muted, array $friends,
                                array $shows)
    {
        $this->dataSource = $dataSource;
        $this->id = $id;
        $this->username = $username;
        $this->passwordHash = $passwordHash;
        $this->email = $email;
        $this->profilePicturePath = $profilePicturePath;
        $this->admin = $admin;
        $this->muted = $muted;
        $this->friends = $friends;
        $this->shows = $shows;

        // TODO: validate values
    }

    public static function createNewUser(IDataSource $dataSource, string $username, string $passwordHash,
                                         string $email): IUser
    {
        return new self($dataSource, null, $username, $passwordHash, $email, null,
            false, false, [], []);
    }
    //endregion

    //region Getters
    public function getId(): int
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

    public function getFriends(): array
    {
        return $this->friends;
    }

    public function getShows(): array
    {
        return $this->shows;
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

    //region Public Members
    public function addFriend(IUser $user): void
    {
        // TODO: validate if not friend already, and if user's id is not null
        // TODO: write changes to datasource
        $this->friends[] = $user;
    }

    public function removeFriend(IUser $user): void
    {
        // TODO: validate if given users id is not null
        // TODO: write changes to datasource
        $key = array_search($user->getId(), array_column($this->friends, 'id'));

        if ($key === false) {
            throw new InvalidArgumentException('Given user was not a friend', 2);
        }
        unset($this->friends[$key]);
    }

    public function save(): void
    {
        try {
            $this->dataSource->saveUser($this);
        } catch (Exception $exception) {
            throw new Exception('Could not save changes', 2, $exception);
        }
    }

    public function remove(): void
    {
        try {
            $this->dataSource->removeUser($this);
        } catch (Exception $exception) {
            throw new Exception('Could not remove user', 2, $exception);
        }
    }
    //endregion
}