<?php

namespace BL\DTO;

use BL\DTO\_Interfaces\IComment;
use BL\DTO\_Interfaces\IShow;
use BL\DTO\_Interfaces\IUser;
use Exception;

class Comment implements IComment
{
    //region Properties
    private ?int $id;
    private ?IShow $show;
    private IUser $author;
    private string $content;
    private ?string $time;
    //endregion

    //region Constructors
    public function __construct(?int $id, ?IShow $show, IUser $author, string $content, ?string $time)
    {
        $this->id = $id;
        $this->show = $show;
        $this->author = $author;
        $this->content = $content;
        $this->time = $time;
    }

    /**
     * @inheritDoc
     */
    public static function createNewComment(IShow $show, IUser $author, string $content): IComment
    {
        if (!$show->getId()) throw new Exception('Show does not exist in data source');
        if (!$author->getId()) throw new Exception('User does not exist in data source');
        if (empty(trim($content))) throw new Exception('Comment cannot be empty', 1);
        return new self(null, $show, $author, $content, null);
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
    /**
     * @inheritDoc
     */
    public function setContent(string $content): IComment
    {
        if (empty(trim($content))) throw new Exception('Comment cannot be empty', 1);
        $this->content = $content;
        return $this;
    }
    //endregion
}