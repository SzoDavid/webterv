<?php

namespace BL\DAO\_Interfaces;

use BL\DTO\_Interfaces\IComment;
use BL\DTO\_Interfaces\IShow;
use Exception;

interface ICommentDAO
{
    /**
     * Returns all the comments that belong to the given show
     * @param IShow $show
     * @return array
     * @throws Exception
     */
    public function getByShow(IShow $show): array;

    /**
     * Creates a comment in the data source
     * @param IComment $comment
     * @return void
     * @throws Exception
     */
    public function save(IComment $comment): void;
    /**
     * Removes comment from data source
     * @param IComment $comment
     * @return void
     * @throws Exception
     */
    public function remove(IComment $comment): void;
}