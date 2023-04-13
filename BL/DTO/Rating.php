<?php

namespace BL\DTO;

use BL\DTO\_Interfaces\IRating;
use BL\DTO\_Interfaces\IShow;
use BL\DTO\_Interfaces\IUser;
use Exception;

class Rating implements IRating
{
    //region Properties
    private IShow $show;
    private IUser $user;
    private int $episodes;
    private ?int $rating;
    //endregion

    //region Constructors
    public function __construct(IShow $show, IUser $user, int $episodes, ?int $rating)
    {
        $this->show = $show;
        $this->user = $user;
        $this->episodes = $episodes;
        $this->rating = $rating;
    }

    /**
     * @inheritDoc
     */
    public static function createNewRating(IShow $show, IUser $user): IRating
    {
        if (!$show->getId()) throw new Exception('Show does not exist in data source');
        if (!$user->getId()) throw new Exception('User does not exist in data source');
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
    /**
     * @inheritDoc
     */
    public function setEpisodesWatched(?int $episodesWatched): IRating
    {
        if (!$episodesWatched) $episodesWatched = 0;
        else if ($episodesWatched < 0 || $episodesWatched > $this->show->getNumEpisodes()) throw new Exception('Invalid number', 1);

        $this->episodes = $episodesWatched;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setRating(?int $rating): IRating
    {
        if ($rating && ($rating < 0 || $rating > 5)) throw new Exception('Invalid number', 1);
        $this->rating = $rating;
        return $this;
    }
    //endregion
}