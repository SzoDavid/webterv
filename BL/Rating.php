<?php

namespace BL;

use BL\_Interfaces\IRating;
use BL\_Interfaces\IShow;
use BL\_Interfaces\IUser;
use BL\DataSource\_Interfaces\IDataSource;
use Exception;

class Rating implements IRating
{
    //region Properties
    private IDataSource $dataSource;
    private ?IShow $show;
    private ?IUser $user;
    private ?int $episodes;
    private ?int $rating;
    //endregion

    //region Constructors
    public function __construct(IDataSource $dataSource, ?IShow $show, ?IUser $user, ?int $episodes, ?int $rating)
    {
        $this->dataSource = $dataSource;
        $this->show = $show;
        $this->user = $user;
        $this->episodes = $episodes;
        $this->rating = $rating;

        // TODO: validate
    }

    public static function createNewRating(IDataSource $dataSource, IShow $show, IUser $user): IRating
    {
        return new self($dataSource, $show, $user, null, null);
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

    //region Public Members
    public function save(): void
    {
        // TODO: check if user and show is not null
        try {
            $this->dataSource->saveRating($this);
        } catch (Exception $exception) {
            throw new Exception('Could not save changes', 5, $exception);
        }
    }

    public function remove(): void
    {
        // TODO: check if user and show is not null
        try {
            $this->dataSource->removeRating($this);
        } catch (Exception $exception) {
            throw new Exception('Could not remove rating', 5, $exception);
        }
    }
    //endregion
}