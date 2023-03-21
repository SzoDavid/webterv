<?php

interface IDataSource
{
    //region User
    public function getAllUsers() : array;
    public function getUserById(int $id) : \_Interfaces\IUser;
    public function getFriends(\_Interfaces\IUser $user) : array;

    public function createUser(string $username, string $password, string $email, ?string $pfpPath, bool $admin,
                               bool $canComment);
    public function updateUser(\_Interfaces\IUser $user);
    public function removeUser(\_Interfaces\IUser $user);
    //endregion

    //region Following
    public function addFriend(\_Interfaces\IUser $followerUser, \_Interfaces\IUser $followedUser);
    public function removeFriend(\_Interfaces\IUser $followerUser, \_Interfaces\IUser $followedUser);
    //endregion

    //region Comment
    public function getComments(\_Interfaces\IShow $show): array;

    public function addComment(\_Interfaces\IUser $user, \_Interfaces\IShow $show, string $content);
    public function removeComment(\_Interfaces\IComment $comment);
    //endregion

    //region Watching
    public function getWatching(\_Interfaces\IShow $show): array;

    public function addWatching(\_Interfaces\IUser $user, \_Interfaces\IShow $show);
    public function updateWatching(\_Interfaces\IUser $user, \_Interfaces\IShow $show, ?int $episodesWatched, ?int $rating);
    public function removeWatching(\_Interfaces\IUser $user, \_Interfaces\IShow $show);
    //endregion
}