<?php

namespace BL\DAO\_Interfaces;

use BL\_Interfaces\IRating;
use BL\_Interfaces\IShow;
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