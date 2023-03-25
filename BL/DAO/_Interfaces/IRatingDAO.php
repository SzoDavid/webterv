<?php

namespace BL\DAO\_Interfaces;

use BL\_Interfaces\IRating;
use BL\_Interfaces\IShow;
use BL\_Interfaces\IUser;
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
     * Returns with the rating that belong to the given show and user
     * @param IShow $show
     * @param IUser $user
     * @return ?IRating
     * @throws Exception
     */
    public function getByShowAndUser(IShow $show, IUser $user): ?IRating;

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