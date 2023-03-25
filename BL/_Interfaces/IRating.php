<?php

namespace BL\_Interfaces;

use BL\DataSource\_Interfaces\IDataSource;
use Exception;

interface IRating
{
    public static function createNewRating(IShow $show, IUser $user): IRating;

    public function getShow(): ?IShow;
    public function getUser(): ?IUser;
    public function getEpisodesWatched(): ?int;
    public function getRating(): ?int;

    public function setEpisodesWatched(?int $episodesWatched): IRating;
    public function setRating(?int $rating): IRating;
}