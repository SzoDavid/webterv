<?php

class ShowStatus implements \_Interfaces\IShowStatus
{
    //region Properties
    private IDataSource $dataSource;
    private \_Interfaces\IUser $user;
    private ?int $episodes;
    private ?int $rating;
    //endregion

    //region Ctor
    function __construct(IDataSource $dataSource, \_Interfaces\IUser $user, ?int $episodes, ?int $rating) {
        $this->dataSource = $dataSource;
        $this->user = $user;
        $this->episodes = $episodes;
        $this->rating = $rating;
    }
    //endregion

    //region Getters
    public function getUser(): \_Interfaces\IUser
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
}