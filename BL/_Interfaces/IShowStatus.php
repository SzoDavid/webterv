<?php

namespace _Interfaces;

interface IShowStatus
{
    public function getUser(): IUser;
    public function getEpisodesWatched(): ?int;
    public function getRating(): ?int;
}