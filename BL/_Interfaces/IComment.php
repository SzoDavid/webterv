<?php

namespace BL\_Interfaces;

use BL\DataSource\_Interfaces\IDataSource;
use Exception;

interface IComment
{
    public static function createNewComment(IDataSource $dataSource, IShow $show, IUser $author, string $content): IComment;

    public function getId(): ?int;
    public function getShow(): IShow;
    public function getAuthor(): IUser;
    public function getContent(): string;
    public function getTime(): string;

    public function setShow(IShow $show): IComment;
    public function setAuthor(IUser $author): IComment;
    public function setContent(string $content): IComment;

    /**
     * Writes changes in datasource
     * @return void
     * @throws Exception
     */
    public function save(): void;
    /**
     * Removes comment from the datasource
     * @return void
     * @throws Exception
     */
    public function remove(): void;
}