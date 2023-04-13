<?php

namespace BL\DTO\_Interfaces;

use Exception;

interface IComment
{
    /**
     * Create a new comment
     * @param IShow $show
     * @param IUser $author
     * @param string $content
     * @return IComment
     * @throws Exception Code 1 if content is empty
     */
    public static function createNewComment(IShow $show, IUser $author, string $content): IComment;

    public function getId(): ?int;
    public function getShow(): IShow;
    public function getAuthor(): IUser;
    public function getContent(): string;
    public function getTime(): string;

    /**
     * @param string $content
     * @return IComment
     * @throws Exception Code 1 if content is empty
     */
    public function setContent(string $content): IComment;
}