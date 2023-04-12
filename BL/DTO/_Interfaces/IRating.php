<?php

namespace BL\DTO\_Interfaces;

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