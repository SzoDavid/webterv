<?php

namespace BL\DAO;

use BL\DataSource\SQLiteDataSource;
use BL\DTO\_Interfaces\IShow;
use BL\DTO\_Interfaces\IUser;
use BL\DTO\User;
use Exception;

class SQLiteUserDAO implements _Interfaces\IUserDAO
{
    //region Properties
    private SQLiteDataSource $dataSource;
    //endregion

    //region Constructor
    public function __construct(SQLiteDataSource $dataSource)
    {
        $this->dataSource = $dataSource;
    }
    //endregion

    //region Public Methods
    /**
     * @inheritDoc
     */
    public function getAll(): array
    {
        try {
            return self::getBySearchText('%');
        } catch (Exception $exception) {
            throw new Exception('Could not get users', 0, $exception);
        }
    }

    /**
     * @inheritDoc
     */
    public function getBySearchText(string $searchText): array
    {
        if (empty(trim($searchText))) $searchText = '%';

        $query = $this->dataSource->getDB()->query("SELECT * FROM User WHERE Username LIKE '%$searchText%'");

        if (!$query) {
            throw new Exception('Could not get values from database: ' . $this->dataSource->getDB()->lastErrorMsg());
        }

        $result = array();

        while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
            $result[] = new User($row['Id'], $row['Username'], $row['Password'], $row['Email'],
                $row['ProfilePicturePath'], $row['Registration'], $row['IsAdmin'] === 1, $row['CanComment'] === 1);
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id): ?IUser
    {
        $query = $this->dataSource->getDB()->query("SELECT * FROM User WHERE Id = '$id'");

        if (!$query) {
            throw new Exception('Could not get values from database: ' . $this->dataSource->getDB()->lastErrorMsg());
        }

        if ($row = $query->fetchArray(SQLITE3_ASSOC)) {
            return new User($row['Id'], $row['Username'], $row['Password'], $row['Email'],
                $row['ProfilePicturePath'], $row['Registration'], $row['IsAdmin'] == 1, $row['CanComment'] == 1);
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function getByEmail(string $email): ?IUser
    {
        $query = $this->dataSource->getDB()->query("SELECT * FROM User WHERE Email = '$email'");

        if (!$query) {
            throw new Exception('Could not get values from database: ' . $this->dataSource->getDB()->lastErrorMsg());
        }

        if ($row = $query->fetchArray(SQLITE3_ASSOC)) {
            return new User($row['Id'], $row['Username'], $row['Password'], $row['Email'],
                $row['ProfilePicturePath'], $row['Registration'], $row['IsAdmin'] == 1, $row['CanComment'] == 1);
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function getFriendsByUser(IUser $user): array
    {
        $id = $user->getId();

        $query = $this->dataSource->getDB()->query("SELECT * FROM User WHERE User.Id IN (SELECT FollowedId FROM Following WHERE FollowerId = '$id')");

        if (!$query) {
            throw new Exception('Could not get values from database: ' . $this->dataSource->getDB()->lastErrorMsg());
        }

        $result = array();

        while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
            $result[] = new User($row['Id'], $row['Username'], $row['Password'], $row['Email'],
                $row['ProfilePicturePath'], $row['Registration'], $row['IsAdmin'] === 1, $row['CanComment'] === 1);
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function getFriendsByUserAndShow(IUser $user, IShow $show): array
    {
        $userId = $user->getId();
        $showId = $show->getId();

        $query = $this->dataSource->getDB()->query("SELECT * FROM User WHERE User.Id IN (SELECT Following.FollowedId FROM Following, Watching WHERE Following.FollowerId=Watching.UserId AND Watching.ShowId='$showId' AND Following.FollowerId = '$userId')");

        if (!$query) {
            throw new Exception('Could not get values from database: ' . $this->dataSource->getDB()->lastErrorMsg());
        }

        $result = array();

        while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
            $result[] = new User($row['Id'], $row['Username'], $row['Password'], $row['Email'],
                $row['ProfilePicturePath'], $row['Registration'], $row['IsAdmin'] === 1, $row['CanComment'] === 1);
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function addFriend(IUser $followerUser, IUser $followedUser): void
    {
        $followerId = $followerUser->getId();
        $followedId = $followedUser->getId();

        $sql = "INSERT INTO Following (FollowerId, FollowedId) VALUES ('$followerId', '$followedId')";
        if (!$this->dataSource->getDB()->exec($sql)) {
            throw new Exception('Could not update database: ' . $this->dataSource->getDB()->lastErrorMsg());
        }
    }

    /**
     * @inheritDoc
     */
    public function removeFriend(IUser $followerUser, IUser $followedUser): void
    {
        $followerId = $followerUser->getId();
        $followedId = $followedUser->getId();

        $sql = "DELETE FROM Following WHERE FollowerId = '$followerId' AND FollowedId = '$followedId'";
        if (!$this->dataSource->getDB()->exec($sql)) {
            throw new Exception('Could not update database: ' . $this->dataSource->getDB()->lastErrorMsg());
        }
    }

    /**
     * @inheritDoc
     */
    public function save(IUser $user): int
    {
        $userId = $user->getId();
        $username = $user->getUsername();
        $email = $user->getEmail();
        $password = $user->getPasswordHash();
        $pfpPath = $user->getProfilePicturePath();
        $admin = $user->isAdmin() ? 1 : 0;
        $canComment = $user->canComment() ? 1 : 0;

        if ($userId == null) {
            $sql = "INSERT INTO User (Username, Email, Password) VALUES ('$username', '$email', '$password')";
        } else {
            try {
                $oldUser = $this->getById($userId);
                if ($oldUser->getProfilePicturePath() && $oldUser->getProfilePicturePath() != $user->getProfilePicturePath()) unlink('../../' . $user->getProfilePicturePath());
            } catch (Exception $ex) {
                throw  new Exception('Failed to get old data from database', 0, $ex);
            }
            $sql = "UPDATE User SET Username = '$username', Email = '$email', Password = '$password', ProfilePicturePath = '$pfpPath', IsAdmin = '$admin', CanComment = '$canComment' WHERE Id = '$userId'";
        }
        if (!$this->dataSource->getDB()->exec($sql)) {
            $msg = $this->dataSource->getDB()->lastErrorMsg();
            $code = 0;

            if (str_contains($msg, 'UNIQUE')) {
                if (str_contains($msg, 'User.Email')) $code = 1;
                else $code = 2;
            }

            throw new Exception('Could not update database: ' . $msg, $code);
        }

        return $userId ?? $this->dataSource->getDB()->lastInsertRowID();
    }

    /**
     * @inheritDoc
     */
    public function remove(IUser $user): void
    {
        if ($user->isAdmin()) {
            $query = $this->dataSource->getDB()->query("SELECT COUNT(*) AS Count FROM User WHERE IsAdmin=1");
            if ($row = $query->fetchArray(SQLITE3_ASSOC)) {
                if ($row['Count'] == 1) {
                    throw new Exception('There must be at least one admin', 10);
                }
            }
        }

        $userId = $user->getId();

        $sql = "DELETE FROM User WHERE Id = '$userId'; " .
            "DELETE FROM Following WHERE FollowerId = '$userId' OR FollowedId = '$userId'; " .
            "DELETE FROM Comment WHERE UserId = '$userId'; " .
            "DELETE FROM Watching WHERE UserId = '$userId'";

        if (!$this->dataSource->getDB()->exec($sql)) {
            throw new Exception('Could not update database: ' . $this->dataSource->getDB()->lastErrorMsg());
        }

        if ($user->getProfilePicturePath()) {
            unlink('../../' . $user->getProfilePicturePath());
        }
    }
    //endregion
}