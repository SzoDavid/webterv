<?php

namespace BL\DataSource;

use BL\ConfigLoader\_Interfaces\IConfigLoader;
use BL\DAO\_Interfaces\ICommentDAO;
use BL\DAO\_Interfaces\IRatingDAO;
use BL\DAO\_Interfaces\IShowDAO;
use BL\DAO\_Interfaces\IUserDAO;
use BL\DAO\SQLiteCommentDAO;
use BL\DAO\SQLiteRatingDAO;
use BL\DAO\SQLiteShowDAO;
use BL\DAO\SQLiteUserDAO;
use BL\DataSource\_Interfaces\IDataSource;
use Exception;
use SQLite3;

class SQLiteDataSource implements IDataSource
{
    //region Properties
    private SQLite3 $db;
    //endregion

    //region Constructor, Destructor
    /**
     * Creates an SQLite Data Source to manage website data
     * @throws Exception - when couldn't connect to database
     */
    function __construct(IConfigLoader $configs) {
        try {
            // TODO: create db if not exists
            $this->db = new SQLite3($configs->getDataSourceConfigs()->getPath(), SQLITE3_OPEN_READWRITE);
        } catch (Exception $exception) {
            throw new Exception('Could not open database', 1, $exception);
        }
    }

    function __destruct() {
        $this->db->close();
    }
    //endregion

    //region Factory Methods
    public function createCommentDAO(): ICommentDAO
    {
        return new SQLiteCommentDAO($this, $this->createUserDAO());
    }

    public function createRatingDAO(): IRatingDAO
    {
        return new SQLiteRatingDAO($this, $this->createUserDAO());
    }

    public function createShowDAO(): IShowDAO
    {
        return new SQLiteShowDAO($this);
    }

    public function createUserDAO(): IUserDAO
    {
        return new SQLiteUserDAO($this);
    }
    //endregion

    public function getDB(): SQLite3
    {
        return $this->db;
    }
}