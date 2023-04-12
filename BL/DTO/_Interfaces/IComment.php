<?php

namespace BL\DTO\_Interfaces;

interface IComment
{
    public static function createNewComment(IShow $show, IUser $author, string $content): IComment;

    public function getId(): ?int;
    public function getShow(): IShow;
    public function getAuthor(): IUser;
    public function getContent(): string;
    public function getTime(): string;

    public function setShow(IShow $show): IComment;
    public function setAuthor(IUser $author): IComment;
    public function setContent(string $content): IComment;
}