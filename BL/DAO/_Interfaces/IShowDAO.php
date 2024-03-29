<?php

namespace BL\DAO\_Interfaces;

use BL\DTO\_Interfaces\IShow;
use BL\DTO\_Interfaces\IUser;
use Exception;

interface IShowDAO
{
    /**
     * Returns with all of the shows
     * @return array
     * @throws Exception
     */
    public function getAll(): array;
    /**
     * Returns with all of the shows, whose title includes given string
     * @param string $searchText
     * @return array
     * @throws Exception
     */
    public function getBySearchText(string $searchText): array;
    /**
     * Returns with show with the given id
     * @param int $id
     * @return ?IShow
     * @throws Exception
     */
    public function getById(int $id): ?IShow;
    /**
     * Returns with all the shows that the given user is watching
     * @param IUser $user
     * @return array
     * @throws Exception
     */
    public function getByUser(IUser $user): array;
    /**
     * Returns with all the shows that are not watched by the given user but is watched by their friends
     * @param IUser $user
     * @return array
     * @throws Exception
     */
    public function getFriendsShowsByUser(IUser $user): array;

    /**
     * If id is null creates a new record, otherwise updates values. Returns with id.
     * @param IShow $show
     * @return int
     * @throws Exception
     */
    public function save(IShow $show): int;
    /**
     * Removes show and all connected references (Comment, Watching)
     * @param IShow $show
     * @return void
     * @throws Exception
     */
    public function remove(IShow $show): void;
}