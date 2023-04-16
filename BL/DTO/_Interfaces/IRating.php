<?php

namespace BL\DTO\_Interfaces;

use Exception;

interface IRating
{
    /**
     * Creates new rating
     * @param IShow $show
     * @param IUser $user
     * @return IRating
     * @throws Exception
     */
    public static function createNewRating(IShow $show, IUser $user): IRating;

    public function getShow(): ?IShow;
    public function getUser(): ?IUser;
    public function getEpisodesWatched(): ?int;
    public function getRating(): ?int;

    /**
     * @param int|null $episodesWatched
     * @return IRating
     * @throws Exception
     */
    public function setEpisodesWatched(?int $episodesWatched): IRating;
    /**
     * @param int|null $rating
     * @return IRating
     * @throws Exception
     */
    public function setRating(?int $rating): IRating;
}