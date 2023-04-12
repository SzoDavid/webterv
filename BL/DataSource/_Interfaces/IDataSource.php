<?php

namespace BL\DataSource\_Interfaces;

use BL\DAO\_Interfaces\ICommentDAO;
use BL\DAO\_Interfaces\IRatingDAO;
use BL\DAO\_Interfaces\IShowDAO;
use BL\DAO\_Interfaces\IUserDAO;

interface IDataSource
{
    public function createCommentDAO(): ICommentDAO;
    public function createRatingDAO(): IRatingDAO;
    public function createShowDAO(): IShowDAO;
    public function createUserDAO(): IUserDAO;
}