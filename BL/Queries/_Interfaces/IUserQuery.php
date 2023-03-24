<?php

namespace BL\Queries\_Interfaces;

use BL\_Interfaces\IUser;
use Exception;

interface IUserQuery
{
    /**
     * Returns with all of the users
     * @return array
     * @throws Exception
     */
    public function getAllUsers(): array;
    /**
     * Returns with all of the users, whose name includes given string
     * @param string $searchText
     * @return array
     * @throws Exception
     */
    public function getUsersBySearchText(string $searchText): array;
    /**
     * Returns with user with the given id
     * @param int $id
     * @return IUser
     * @throws Exception
     */
    public function getUserById(int $id): IUser;
    /**
     * Returns with user with given email address
     * @param string $email
     * @return IUser
     * @throws Exception
     */
    public function getUserByEmail(string $email): IUser;
}