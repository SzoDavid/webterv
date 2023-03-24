<?php

namespace BL;

use BL\_Interfaces\IComment;
use BL\_Interfaces\IRating;
use BL\_Interfaces\IShow;
use BL\DataSource\_Interfaces\IDataSource;
use Exception;

class Show implements IShow
{
    //region Properties
    private IDataSource $dataSource;
    private ?int $id;
    private string $title;
    private int $numEpisodes;
    private ?string $description;
    private ?string $coverPath;
    private ?string $trailerPath;
    private ?string $ostPath;
    private array $comments;
    private array $ratings;
    //endregion

    //region Constructors
    public function __construct(IDataSource $dataSource, ?int $id, string $title, int $numEpisodes,
                                ?string $description, ?string $coverPath, ?string $trailerPath, ?string $ostPath,
                                array $comments, array $ratings)
    {
        $this->dataSource = $dataSource;
        $this->id = $id;
        $this->title = $title;
        $this->numEpisodes = $numEpisodes;
        $this->description = $description;
        $this->coverPath = $coverPath;
        $this->trailerPath = $trailerPath;
        $this->ostPath = $ostPath;
        $this->comments = $comments;
        $this->ratings = $ratings;

        // TODO: validate values
    }

    public static function createNewShow(IDataSource $dataSource, string $title, int $numEpisodes, ?string $description,
                                         ?string $coverPath, ?string $trailerPath, ?string $ostPath): IShow
    {
        return new self($dataSource, null, $title, $numEpisodes, $description, $coverPath, $trailerPath,
            $ostPath, [], []);
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

    public function getAllComments(): array
    {
        return $this->comments;
    }

    public function getAllRatings(): array
    {
        return $this->ratings;
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

    //region Public Members
    public function addComment(IComment $comment): void
    {
        // TODO: validate if not added already, and if id is not null
        $this->comments[] = $comment;
    }

    public function addRating(IRating $rating): void
    {
        // TODO: validate if not friend already, and if user's id is not null
        // TODO: write changes to datasource
        $this->ratings[] = $rating;
    }

    public function removeComment(IComment $comment): void
    {
        // TODO: validate if id is not null
        $key = array_search($comment->getId(), array_column($this->comments, 'id'));

        if ($key !== false) {
            try {
                $comment->remove();
            } catch (Exception $exception) {
                throw new Exception('Could not remove comment from datasource', 3, $exception);
            }

            unset($this->comments[$key]);
        }
    }

    public function removeRating(IRating $rating): void
    {
        // NOTE: in rare events, this may fail, because this will compare the whole user, not just its id
        $key = array_search($rating->getUser(), array_column($this->ratings, 'User'), true);

        if ($key !== false) {
            try {
                $rating->remove();
            } catch (Exception $exception) {
                throw new Exception('Could not remove rating from datasource', 3, $exception);
            }

            unset($this->ratings[$key]);
        }
    }

    public function save(): void
    {
        try {
            $this->dataSource->saveShow($this);
        } catch (Exception $exception) {
            throw new Exception('Could not save changes', 3, $exception);
        }
    }

    public function remove(): void
    {
        try {
            $this->dataSource->removeShow($this);
        } catch (Exception $exception) {
            throw new Exception('Could not remove show', 3, $exception);
        }
    }
    //endregion
}