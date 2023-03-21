<?php

namespace _Interfaces;

interface IUser
{
    public function getId(): int;
    public function getUsername(): string;
    public function getPasswordHash(): string;
    public function getEmail(): string;
    public function getProfilePicturePath(): ?string;
    public function getFriends(): array;
    public function isAdmin(): bool;
    public function canComment(): bool;

    public function addFriend(IUser $user);
    public function removeFriend(IUser $user);

    public function writeComment(\_Interfaces\IShow $show, string $content);
    public function removeComment(IComment $comment);

    public function addShowToList(\_Interfaces\IShow $show);
    public function updateRating(\_Interfaces\IShow $show, ?int $episodesWatched, ?int $rating);
    public function removeShowFromList(\_Interfaces\IShow $show);

    public function update(?string $username, ?string $email, ?string $profilePicturePath, ?string $passwordHash);
    public function remove();
}