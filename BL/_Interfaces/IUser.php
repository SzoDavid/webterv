<?php

namespace BL\_Interfaces;

use BL\DataSource\_Interfaces\IDataSource;
use Exception;

interface IUser
{
    public static function createNewUser(IDataSource $dataSource, string $username, string $passwordHash, string $email): IUser;

    public function getId(): ?int;
    public function getUsername(): string;
    public function getPasswordHash(): string;
    public function getEmail(): string;
    public function getProfilePicturePath(): ?string;
    public function isAdmin(): bool;
    public function isMuted(): bool;
    public function getFriends(): array;
    public function getShows(): array;

    public function setUsername(string $username): IUser;
    public function setPasswordHash(string $passwordHash): IUser;
    public function setEmail(string $email): IUser;
    public function setProfilePicturePath(?string $profilePicturePath): IUser;
    public function setAdmin(bool $isAdmin): IUser;
    public function setMuted(bool $isMuted): IUser;

    public function addFriend(IUser $user): void;
    public function removeFriend(IUser $user): void;

    /**
     * If exists, writes changes in datasource, else creates new record
     * @return void
     * @throws Exception
     */
    public function save(): void;
    /**
     * Removes user from the datasource
     * @return void
     * @throws Exception
     */
    public function remove(): void;
}