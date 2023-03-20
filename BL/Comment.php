<?php

class Comment implements \_Interfaces\IComment
{
    //region Properties
    private IDataSource $dataSource;
    private int $id;
    private \_Interfaces\IUser $author;
    private string $content;
    private string $time;
    //endregion

    //region Ctor
    function __construct(IDataSource $dataSource, int $id, \_Interfaces\IUser $author, string $content, string $time) {
        $this->dataSource = $dataSource;
        $this->id = $id;
        $this->author = $author;
        $this->content = $content;
        $this->time = $time;
    }
    //endregion

    public function getAuthor(): \_Interfaces\IUser
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
}