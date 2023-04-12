<?php

namespace BL\DTO\_Interfaces;

interface IShow
{
    public static function createNewShow(string $title, int $numEpisodes, ?string $description,
                                         ?string $coverPath, ?string $trailerPath, ?string $ostPath): IShow;

    public function getId(): ?int;
    public function getTitle(): string;
    public function getNumEpisodes(): int;
    public function getCoverPath(): ?string;
    public function getTrailerPath(): ?string;
    public function getOstPath(): ?string;
    public function getDescription(): ?string;

    public function setTitle(string $title): IShow;
    public function setNumEpisodes(int $numEpisodes): IShow;
    public function setCoverPath(?string $coverPath): IShow;
    public function setTrailerPath(?string $trailerPath): IShow;
    public function setOstPath(?string $ostPath): IShow;
    public function setDescription(?string $description): IShow;
}
