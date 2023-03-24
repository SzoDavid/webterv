<?php

namespace BL\Queries\_Interfaces;

use BL\_Interfaces\IShow;
use Exception;

interface IShowQuery
{
    /**
     * Returns with all of the shows
     * @return array
     * @throws Exception
     */
    public function getAllShows(): array;
    /**
     * Returns with all of the shows, whose title includes given string
     * @param string $searchText
     * @return array
     * @throws Exception
     */
    public function getShowsBySearchText(string $searchText): array;
    /**
     * Returns with show with the given id
     * @param int $id
     * @return IShow
     * @throws Exception
     */
    public function getShowById(int $id): IShow;
}