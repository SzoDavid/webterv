<?php

namespace BL\DAO\_Interfaces;

use BL\DTO\_Interfaces\IRating;
use BL\DTO\_Interfaces\IShow;
use BL\DTO\_Interfaces\IUser;
use Exception;

interface IRatingDAO
{
    /**
     * Returns with all of the ratings that belong to the given show
     * @param IShow $show
     * @return array
     * @throws Exception
     */
    public function getByShow(IShow $show): array;
    /**
     * Returns with all of the ratings that belong to the given user
     * @param IUser $user
     * @return array
     * @throws Exception
     */
    public function getByUser(IUser $user): array;
    /**
     * Returns with the rating that belong to the given show and user
     * @param IShow $show
     * @param IUser $user
     * @return ?IRating
     * @throws Exception
     */
    public function getByShowAndUser(IShow $show, IUser $user): ?IRating;
    /**
     * Returns with the average rating of the given show
     * @param IShow $show
     * @return float
     * @throws Exception
     */
    public function getAverageRatingByShow(IShow $show): float;
    /**
     * Returns with all of the ratings that belong to the given user and is not up to date
     * @param IUser $user
     * @return array
     * @throws Exception
     */
    public function getUnwatchedByUser(IUser $user): array;

    /**
     * Creates or updates a rating in the data source
     * @param IRating $rating
     * @return void
     * @throws Exception
     */
    public function save(IRating $rating): void;
    /**
     * Removes rating from data source
     * @param IRating $rating
     * @return void
     * @throws Exception
     */
    public function remove(IRating $rating): void;
}