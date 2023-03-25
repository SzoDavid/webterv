<?php

namespace BL;

use BL\_Interfaces\IRating;
use BL\_Interfaces\IShow;
use BL\_Interfaces\IUser;

class Rating implements IRating
{
    //region Properties
    private ?IShow $show;
    private ?IUser $user;
    private int $episodes;
    private ?int $rating;
    //endregion

    //region Constructors
    public function __construct(?IShow $show, ?IUser $user, int $episodes, ?int $rating)
    {
        $this->show = $show;
        $this->user = $user;
        $this->episodes = $episodes;
        $this->rating = $rating;

        // TODO: validate
    }

    public static function createNewRating(IShow $show, IUser $user): IRating
    {
        return new self($show, $user, 0, null);
    }

    //endregion

    //region Getters
    public function getShow(): ?IShow
    {
        return $this->show;
    }

    public function getUser(): ?IUser
    {
        return $this->user;
    }

    public function getEpisodesWatched(): ?int
    {
        return $this->episodes;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }
    //endregion

    //region Setters
    public function setEpisodesWatched(?int $episodesWatched): IRating
    {
        // TODO: validate
        $this->episodes = $episodesWatched;
        return $this;
    }

    public function setRating(?int $rating): IRating
    {
        // TODO: validate
        $this->rating = $rating;
        return $this;
    }
    //endregion
}