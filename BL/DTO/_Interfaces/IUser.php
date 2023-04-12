<?php

namespace BL\DTO\_Interfaces;

interface IUser
{
    public static function createNewUser(string $username, string $passwordHash, string $email): IUser;

    public function getId(): ?int;
    public function getUsername(): string;
    public function getPasswordHash(): string;
    public function getEmail(): string;
    public function getProfilePicturePath(): ?string;
    public function getTimestampOfRegistration(): string;
    public function isAdmin(): bool;
    public function canComment(): bool;

    public function setUsername(string $username): IUser;
    public function setPasswordHash(string $passwordHash): IUser;
    public function setEmail(string $email): IUser;
    public function setProfilePicturePath(?string $profilePicturePath): IUser;
    public function setAdmin(bool $isAdmin): IUser;
    public function setCanComment(bool $isMuted): IUser;
}