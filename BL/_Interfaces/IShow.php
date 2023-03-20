<?php

namespace _Interfaces;

interface IShow
{
    public function getId(): int;
    public function getTitle(): string;
    public function getNumEpisodes(): int;
    public function getCoverPath(): ?string;
    public function getTrailerPath(): ?string;
    public function getOstPath(): ?string;
    public function getDescription(): ?string;
    public function getComments(): array;
    public function getWatching(): array;

    public function addComment(IUser $user, string $content);

    public function update(?string $title, ?int $numEpisode, ?string $coverPath,
                           ?string $trailerPath, ?string $ostPath, ?string $description);
}
