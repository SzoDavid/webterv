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
use BL\DTO\_Interfaces\IUser;
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
            $this->db = new SQLite3($configs->getDataSourceConfigs()->getPath());
            $this->createTables();

            if ($configs->isAdminGenerationEnabled() && $configs->getDefaultAdminUser()) {
                $this->generateDefaultAdmin($configs->getDefaultAdminUser());
            }
        } catch (Exception $exception) {
            throw new Exception('Could not open database', 0, $exception);
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
        return new SQLiteRatingDAO($this, $this->createUserDAO(), $this->createShowDAO());
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

    //region Getters
    public function getDB(): SQLite3
    {
        return $this->db;
    }
    //endregion

    //region Private Methods
    /**
     * @throws Exception
     */
    private function createTables(): void
    {
        $commands = ['CREATE TABLE IF NOT EXISTS Comment
                        (
                        Id       INTEGER                        not null
                            primary key autoincrement
                            unique,
                        UserId   INTEGER                        not null,
                        ShowId   INTEGER                        not null,
                        Content  TEXT                           not null,
                        DateTime TEXT default CURRENT_TIMESTAMP not null
                        )',
            'CREATE TABLE IF NOT EXISTS Following
                        (
                        FollowerId INTEGER not null,
                        FollowedId INTEGER not null,
                        unique (FollowedId, FollowerId)
                        )',
            'CREATE TABLE IF NOT EXISTS Show
                        (
                            Id          INTEGER           not null
                                primary key autoincrement
                                unique,
                            Title       TEXT              not null,
                            NumEpisodes INTEGER           not null,
                            Description TEXT default NULL,
                            CoverPath   TEXT default NULL not null,
                            TrailerPath TEXT default NULL,
                            OstPath     TEXT default NULL not null
                        )',
            'CREATE TABLE IF NOT EXISTS User
                        (
                            Id                 INTEGER           not null
                                primary key autoincrement
                                unique,
                            Username           TEXT              not null
                                unique,
                            Email              TEXT              not null
                                unique,
                            Password           TEXT              not null,
                            ProfilePicturePath TEXT    default NULL,
                            IsAdmin            INTEGER default 0 not null,
                            CanComment         INTEGER default 1 not null,
                            Registration       TEXT default CURRENT_TIMESTAMP not null,
                            Visibility             INTEGER default 2                 not null
                        )',
            'CREATE TABLE IF NOT EXISTS Watching
                        (
                            UserId   INTEGER not null,
                            ShowId   INTEGER not null,
                            Episodes INTEGER default NULL,
                            Rating   INTEGER default NULL,
                            unique (UserId, ShowId)
                        )'];
        foreach ($commands as $command) {
            if (!$this->db->exec($command)) {
                throw new Exception($this->db->lastErrorMsg());
            }
        }
    }

    /**
     * @throws Exception
     */
    private function generateDefaultAdmin(IUser $user): void
    {
        try {
            $query = $this->db->query("SELECT COUNT(*) AS Count FROM User");

            if ($row = $query->fetchArray(SQLITE3_ASSOC)) {
                if ($row['Count'] == 0) {
                    $this->createUserDAO()->save($user);
                }
            }
        } catch (Exception $ex) {
            throw new Exception('Failed to generate default admin', 0, $ex);
        }
    }
    //endregion
}