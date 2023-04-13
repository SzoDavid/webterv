<?php

namespace BL\DTO\_Interfaces;

use Exception;

interface IShow
{
    /**
     * @param string $title
     * @param int $numEpisodes
     * @param string|null $description
     * @param string $coverPath
     * @param string|null $trailerPath
     * @param string|null $ostPath
     * @return IShow
     * @throws Exception Code 1 if title is empty, 2 if numEpisodes is invalid, 3 if coverPath is empty
     */
    public static function createNewShow(string $title, int $numEpisodes, ?string $description,
                                         string $coverPath, ?string $trailerPath, ?string $ostPath): IShow;

    public function getId(): ?int;
    public function getTitle(): string;
    public function getNumEpisodes(): int;
    public function getCoverPath(): string;
    public function getTrailerPath(): ?string;
    public function getOstPath(): ?string;
    public function getDescription(): ?string;

    /**
     * @param string $title
     * @return IShow
     * @throws Exception code 1 if title is empty
     */
    public function setTitle(string $title): IShow;
    /**
     * @param int $numEpisodes
     * @return IShow
     * @throws Exception code 2 if numEpisodes is invalid
     */
    public function setNumEpisodes(int $numEpisodes): IShow;
    /**
     * @param string $coverPath
     * @return IShow
     * @throws Exception code 1 if coverPath is empty
     */
    public function setCoverPath(string $coverPath): IShow;
    public function setTrailerPath(?string $trailerPath): IShow;
    public function setOstPath(?string $ostPath): IShow;
    public function setDescription(?string $description): IShow;
}
