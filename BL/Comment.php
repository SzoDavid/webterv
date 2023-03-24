<?php

namespace BL;

use BL\_Interfaces\IComment;
use BL\_Interfaces\IShow;
use BL\_Interfaces\IUser;
use BL\DataSource\_Interfaces\IDataSource;
use Exception;

class Comment implements IComment
{
    //region Properties
    private IDataSource $dataSource;
    private ?int $id;
    private ?IShow $show;
    private IUser $author;
    private string $content;
    private string $time;
    //endregion

    //region Constructors
    public function __construct(IDataSource $dataSource, ?int $id, ?IShow $show, IUser $author, string $content, string $time)
    {
        $this->dataSource = $dataSource;
        $this->id = $id;
        $this->show = $show;
        $this->author = $author;
        $this->content = $content;
        $this->time = $time;
        // TODO: validate
    }

    public static function createNewComment(IDataSource $dataSource, IShow $show, IUser $author, string $content): IComment
    {
        return new self($dataSource, null, $show, $author, $content, null);
    }
    //endregion

    //region Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getShow(): IShow
    {
        return $this->show;
    }

    public function getAuthor(): IUser
    {
        return $this->author;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getTime(): string
    {
        return $this->time;
    }
    //endregion

    //region Setters
    public function setShow(IShow $show): IComment
    {
        // TODO: validate
        $this->show = $show;
        return $this;
    }

    public function setAuthor(IUser $author): IComment
    {
        // TODO: validate
        $this->author = $author;
        return $this;
    }

    public function setContent(string $content): IComment
    {
        // TODO: validate
        $this->content = $content;
        return $this;
    }
    //endregion

    //region Public Members
    public function save(): void
    {
        // TODO: throw error if id is not null?
        try {
            $this->dataSource->saveComment($this);
        } catch (Exception $exception) {
            throw new Exception('Could not save changes', 4, $exception);
        }
    }

    public function remove(): void
    {
        try {
            $this->dataSource->removeComment($this);
        } catch (Exception $exception) {
            throw new Exception('Could not remove comment', 4, $exception);
        }
    }
    //endregion
}