<?php

namespace BL\DTO\_Interfaces;

use BL\_enums\EListVisibility;
use Exception;

interface IUser
{
    /**
     * @param string $username
     * @param string $passwordHash
     * @param string $email
     * @param bool $admin
     * @return IUser
     * @throws Exception - Code 21 if username is empty, 22 if password is empty, 23 if email is invalid
     */
    public static function createNewUser(string $username, string $passwordHash, string $email, bool $admin=false): IUser;

    public function getId(): ?int;
    public function getUsername(): string;
    public function getPasswordHash(): string;
    public function getEmail(): string;
    public function getProfilePicturePath(): ?string;
    public function getTimestampOfRegistration(): string;
    public function isAdmin(): bool;
    public function canComment(): bool;
    public function getListVisibility(): EListVisibility;

    /**
     * @param string $username
     * @return IUser
     * @throws Exception - Code 21 if username is empty
     */
    public function setUsername(string $username): IUser;
    /**
     * @param string $passwordHash
     * @return IUser
     * @throws Exception - Code 22 if password is empty
     */
    public function setPasswordHash(string $passwordHash): IUser;
    /**
     * @param string $email
     * @return IUser
     * @throws Exception - Code 23 if email is invalid
     */
    public function setEmail(string $email): IUser;
    public function setProfilePicturePath(?string $profilePicturePath): IUser;
    public function setAdmin(bool $isAdmin): IUser;
    public function setCanComment(bool $canComment): IUser;
    public function setListVisibility(EListVisibility $listVisibility): IUser;
}