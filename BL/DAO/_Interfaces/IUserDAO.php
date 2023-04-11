<?php

namespace BL\DAO\_Interfaces;

use BL\_Interfaces\IUser;
use Exception;

interface IUserDAO
{
    /**
     * Returns with all of the users
     * @return array
     * @throws Exception
     */
    public function getAll(): array;
    /**
     * Returns with all of the users, whose name includes given string
     * @param string $searchText
     * @return array
     * @throws Exception
     */
    public function getBySearchText(string $searchText): array;
    /**
     * Returns with user with the given id
     * @param int $id
     * @return ?IUser
     * @throws Exception
     */
    public function getById(int $id): ?IUser;
    /**
     * Returns with user with given email address
     * @param string $email
     * @return ?IUser
     * @throws Exception
     */
    public function getByEmail(string $email): ?IUser;

    /**
     * Returns with all the users that are the given user's friends
     * @param IUser $user
     * @return array
     * @throws Exception
     */
    public function getFriendsByUser(IUser $user): array;

    /**
     * Creates a followed-follower reference between the given users
     * @param IUser $followerUser
     * @param IUser $followedUser
     * @return void
     * @throws Exception
     */
    public function addFriend(IUser $followerUser, IUser $followedUser): void;
    /**
     * Removes a followed-follower reference between the given users
     * @param IUser $followerUser
     * @param IUser $followedUser
     * @return void
     * @throws Exception
     */
    public function removeFriend(IUser $followerUser, IUser $followedUser): void;

    /**
     * If id is null creates a new record with the given uname, email and password, otherwise updates all values. Returns with the id of the record.
     * @param IUser $user
     * @return int
     * @throws Exception
     */
    public function save(IUser $user): int;
    /**
     * Removes user and all connected references (Following, Comment, Watching)
     * @param IUser $user
     * @return void
     * @throws Exception
     */
    public function remove(IUser $user): void;
}