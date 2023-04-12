<?php

namespace BL\DTO;

use BL\DTO\_Interfaces\IShow;

class Show implements IShow
{
    //region Properties
    private ?int $id;
    private string $title;
    private int $numEpisodes;
    private ?string $description;
    private ?string $coverPath;
    private ?string $trailerPath;
    private ?string $ostPath;
    //endregion

    //region Constructors
    public function __construct(?int $id, string $title, int $numEpisodes,
                                ?string $description, ?string $coverPath, ?string $trailerPath, ?string $ostPath)
    {
        $this->id = $id;
        $this->title = $title;
        $this->numEpisodes = $numEpisodes;
        $this->description = $description;
        $this->coverPath = $coverPath;
        $this->trailerPath = $trailerPath;
        $this->ostPath = $ostPath;

        // TODO: validate values
    }

    public static function createNewShow(string $title, int $numEpisodes, ?string $description,
                                         ?string $coverPath, ?string $trailerPath, ?string $ostPath): IShow
    {
        return new self(null, $title, $numEpisodes, $description, $coverPath, $trailerPath,
            $ostPath);
    }
    //endregion

    //region Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getNumEpisodes(): int
    {
        return $this->numEpisodes;
    }

    public function getCoverPath(): ?string
    {
        return $this->coverPath;
    }

    public function getTrailerPath(): ?string
    {
        return $this->trailerPath;
    }

    public function getOstPath(): ?string
    {
        return $this->ostPath;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
    //endregion

    //region Setters
    public function setTitle(string $title): IShow
    {
        // TODO: validate
        $this->title = $title;
        return $this;
    }

    public function setNumEpisodes(int $numEpisodes): IShow
    {
        // TODO: validate
        $this->numEpisodes = $numEpisodes;
        return $this;
    }

    public function setCoverPath(?string $coverPath): IShow
    {
        // TODO: validate, if empty string, set to null
        $this->coverPath = $coverPath;
        return $this;
    }

    public function setTrailerPath(?string $trailerPath): IShow
    {
        // TODO: validate, if empty string, set to null
        $this->trailerPath = $trailerPath;
        return $this;
    }

    public function setOstPath(?string $ostPath): IShow
    {
        // TODO: validate, if empty string, set to null
        $this->ostPath = $ostPath;
        return $this;
    }

    public function setDescription(?string $description): IShow
    {
        // TODO: validate, if empty string, set to null
        $this->description = $description;
        return $this;
    }
    //endregion
}