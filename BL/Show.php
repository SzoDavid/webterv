<?php

class Show implements \_Interfaces\IShow
{
    //region Properties
    private IDataSource $dataSource;
    private int $id;
    private string $title;
    private int $numEpisodes;
    private ?string $description;
    private ?string $coverPath;
    private ?string $trailerPath;
    private ?string $ostPath;
    //endregion

    //region Ctor
    function __construct(IDataSource $dataSource, int $id, string $title, int $numEpisodes, ?string $description,
                         ?string $coverPath, ?string $trailerPath, ?string $ostPath) {
        $this->dataSource = $dataSource;
        $this->id = $id;
        $this->title = $title;
        $this->numEpisodes = $numEpisodes;
        $this->description = $description;
        $this->coverPath = $coverPath;
        $this->trailerPath = $trailerPath;
        $this->ostPath = $ostPath;
    }
    //endregion

    //region Getters
    public function getId(): int
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

    public function getComments(): array
    {
        return $this->dataSource->getComments($this);
    }

    public function getWatching(): array
    {
        return $this->dataSource->getWatching($this);
    }
    //endregion

    //region Public Members
    public function update(?string $title, ?int $numEpisode, ?string $coverPath, ?string $trailerPath, ?string $ostPath, ?string $description)
    {
        // TODO: Implement update() method.
    }
    //endregion
}