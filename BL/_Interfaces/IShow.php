<?php

namespace BL\_Interfaces;

use BL\DataSource\_Interfaces\IDataSource;
use Exception;

interface IShow
{
    public static function createNewShow(IDataSource $dataSource, string $title, int $numEpisodes, ?string $description,
                                         ?string $coverPath, ?string $trailerPath, ?string $ostPath): IShow;

    public function getId(): ?int;
    public function getTitle(): string;
    public function getNumEpisodes(): int;
    public function getCoverPath(): ?string;
    public function getTrailerPath(): ?string;
    public function getOstPath(): ?string;
    public function getDescription(): ?string;
    public function getAllComments(): array;
    public function getAllRatings(): array;

    public function setTitle(string $title): IShow;
    public function setNumEpisodes(int $numEpisodes): IShow;
    public function setCoverPath(?string $coverPath): IShow;
    public function setTrailerPath(?string $trailerPath): IShow;
    public function setOstPath(?string $ostPath): IShow;
    public function setDescription(?string $description): IShow;

    public function addComment(IComment $comment): void;
    public function addRating(IRating $rating): void;

    /**
     * Removes comment.
     * WARNING: This will attempt to remove the comment from datasource!
     * @param IComment $comment
     * @return void
     * @throws Exception
     */
    public function removeComment(IComment $comment): void;
    /**
     * Removes rating.
     * WARNING: This will attempt to remove the rating from datasource!
     * @param IRating $rating
     * @return void
     * @throws Exception
     */
    public function removeRating(IRating $rating): void;

    /**
     * If exists, writes changes in datasource, else creates new record
     * @return void
     * @throws Exception
     */
    public function save(): void;
    /**
     * Removes show from the datasource
     * @return void
     * @throws Exception
     */
    public function remove(): void;
}
