<?php

namespace BL\DTO;

use BL\DTO\_Interfaces\IShow;
use Exception;

class Show implements IShow
{
    //region Properties
    private ?int $id;
    private string $title;
    private int $numEpisodes;
    private ?string $description;
    private string $coverPath;
    private ?string $trailerPath;
    private ?string $ostPath;
    //endregion

    //region Constructors
    public function __construct(?int $id, string $title, int $numEpisodes,
                                ?string $description, string $coverPath, ?string $trailerPath, ?string $ostPath)
    {
        $this->id = $id;
        $this->title = $title;
        $this->numEpisodes = $numEpisodes;
        $this->description = $description;
        $this->coverPath = $coverPath;
        $this->trailerPath = $trailerPath;
        $this->ostPath = $ostPath;
    }

    /**
     * @inheritDoc
     */
    public static function createNewShow(string $title, int $numEpisodes, ?string $description,
                                         string $coverPath, ?string $trailerPath, ?string $ostPath): IShow
    {
        if (empty(trim($title))) throw new Exception('Title is empty', 1);
        if ($numEpisodes < 0) throw new Exception("Number of episodes cannot be negative (received: $numEpisodes)", 2);
        if (empty(trim($coverPath))) throw new Exception('Cover path is empty', 3);
        if (empty(trim($description))) $description = null;
        if (empty(trim($trailerPath))) $trailerPath = null;
        if (empty(trim($ostPath))) $ostPath = null;

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

    public function getCoverPath(): string
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
    /**
     * @inheritDoc
     */
    public function setTitle(string $title): IShow
    {
        if (empty(trim($title))) throw new Exception('Title is empty', 1);
        $this->title = $title;
        return $this;
    }
    /**
     * @inheritDoc
     */
    public function setNumEpisodes(int $numEpisodes): IShow
    {
        if ($numEpisodes < 0) throw new Exception("Number of episodes cannot be negative (received: $numEpisodes)", 2);
        $this->numEpisodes = $numEpisodes;
        return $this;
    }
    /**
     * @inheritDoc
     */
    public function setCoverPath(string $coverPath): IShow
    {
        if (empty(trim($coverPath))) throw new Exception('Cover path is empty', 3);
        $this->coverPath = $coverPath;
        return $this;
    }

    public function setTrailerPath(?string $trailerPath): IShow
    {
        if (empty(trim($trailerPath))) $trailerPath = null;
        $this->trailerPath = $trailerPath;
        return $this;
    }

    public function setOstPath(?string $ostPath): IShow
    {
        if (empty(trim($ostPath))) $ostPath = null;
        $this->ostPath = $ostPath;
        return $this;
    }

    public function setDescription(?string $description): IShow
    {
        if (empty(trim($description))) $description = null;
        $this->description = $description;
        return $this;
    }
    //endregion
}