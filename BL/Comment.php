<?php

namespace BL;

use BL\_Interfaces\IComment;
use BL\_Interfaces\IShow;
use BL\_Interfaces\IUser;

class Comment implements IComment
{
    //region Properties
    private ?int $id;
    private ?IShow $show;
    private IUser $author;
    private string $content;
    private string $time;
    //endregion

    //region Constructors
    public function __construct(?int $id, ?IShow $show, IUser $author, string $content, string $time)
    {
        $this->id = $id;
        $this->show = $show;
        $this->author = $author;
        $this->content = $content;
        $this->time = $time;
        // TODO: validate
    }

    public static function createNewComment(IShow $show, IUser $author, string $content): IComment
    {
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
}