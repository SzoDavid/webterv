<?php

namespace BL\DataSource\_Interfaces;

use BL\_Interfaces\IComment;
use BL\_Interfaces\IRating;
use BL\_Interfaces\IShow;
use BL\_Interfaces\IUser;
use Exception;

/**
 * Interface for representing the operations that can be made with the data source
 */
interface IDataSource
{
    /**
     * Returns users whose username includes searchText, will not include friends or shows
     * @param string $searchText
     * @return array
     * @throws Exception
     */
    public function getUsersBySearchText(string $searchText): array;
    /**
     * Returns with user with given Id, and friends, and shows
     * @param int $id
     * @return IUser
     * @throws Exception
     */
    public function getUserById(int $id): IUser;
    /**
     * Returns with user with given email address, without friends or shows
     * @param string $email
     * @return IUser
     * @throws Exception
     */
    public function getUserByEmail(string $email): IUser;

    /**
     * If id is null creates a new record with the given uname, email and password, otherwise updates all values.
     * @param IUser $user
     * @return void
     * @throws Exception
     */
    public function saveUser(IUser $user): void;
    /**
     * Removes user and all connected references (Following, Comment, Watching)
     * @param IUser $user
     * @return void
     * @throws Exception
     */
    public function removeUser(IUser $user): void;

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
     * Returns with shows whose title includes given string. Will not include comments, and ratings will not include the show
     * @param string $searchText
     * @return array
     * @throws Exception
     */
    public function getShowsBySearchText(string $searchText): array;
    /**
     * Returns with show with the given id, including comments and ratings. Ratings will not include the show
     * @param int $id
     * @return IShow
     * @throws Exception
     */
    public function getShowById(int $id): IShow;

    /**
     * If id is null creates a new record, otherwise updates values
     * @param IShow $show
     * @return void
     * @throws Exception
     */
    public function saveShow(IShow $show): void;
    /**
     * Removes show and all connected references (Comment, Watching)
     * @param IShow $show
     * @return void
     * @throws Exception
     */
    public function removeShow(IShow $show): void;

    /**
     * Creates a comment in the data source
     * @param IComment $comment
     * @return void
     * @throws Exception
     */
    public function saveComment(IComment $comment): void;
    /**
     * Removes comment from data source
     * @param IComment $comment
     * @return void
     * @throws Exception
     */
    public function removeComment(IComment $comment): void;

    /**
     * Creates or updates a rating in the data source
     * @param IRating $rating
     * @return void
     * @throws Exception
     */
    public function saveRating(IRating $rating): void;
    /**
     * Removes rating from data source
     * @param IRating $rating
     * @return void
     * @throws Exception
     */
    public function removeRating(IRating $rating): void;
}