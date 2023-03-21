<?php

namespace DataSource;

use _Interfaces\IShow;
use _Interfaces\IUser;

require __DIR__ . '/_Interfaces/IDataSource.php';
require __DIR__ . '/../_Interfaces/IUser.php';
require __DIR__ . '/../_Interfaces/IShow.php';
require __DIR__ . '/../User.php';
require __DIR__ . '/../Comment.php';
require __DIR__ . '/../ShowStatus.php';

class SQLiteDataSource implements \IDataSource
{
    //region Properties
    private \SQLite3 $db;
    //endregion

    //region Ctor
    function __construct(string $dbpath) {
        try {
            $this->db = new \SQLite3($dbpath, SQLITE3_OPEN_READWRITE);
        } catch (\Exception $exception) {
            throw new \Exception("Couldn't open database", $exception);
        }
    }
    //endregion

    //region User
    public function getAllUsers(): array
    {
        $sql = "SELECT * FROM User";

        return $this->getUsers($sql);
    }

    public function getUserById(int $id): \_Interfaces\IUser
    {
        $sql = "SELECT * FROM User WHERE Id=$id";
        return $this->getUsers($sql)[0];
    }

    public function getFriends(\_Interfaces\IUser $user): array
    {
        $userId = $user->getId();
        $sql = "SELECT * FROM User WHERE User.Id IN (SELECT FollowedId FROM Following WHERE FollowerId = $userId)";
        return $this->getUsers($sql);
    }

    public function createUser(string $username, string $password, string $email, ?string $pfpPath, bool $admin, bool $canComment)
    {
        $sql = "INSERT INTO User (Username, Email, Password, ProfilePicturePath ) VALUES ($username, $email, $password, $pfpPath)";
        if (!$this->db-exec($sql)) {
            throw new \Exception("Couldn't update database");
        }
    }

    public function updateUser(IUser $user)
    {
        $userId = $user->getId();
        $username = $user->getUsername();
        $email = $user->getEmail();
        $password = $user->getPasswordHash();
        $pfpPath = $user->getProfilePicturePath();
        $admin = $user->isAdmin() ? 1 : 0;
        $canComment = $user->canComment() ? 1 : 0;

        $sql = "UPDATE User SET Username = $username, Email = $email, Password = $password, ProfilePicturePath = $pfpPath, IsAdmin = $admin, CanComment = $canComment WHERE Id = $userId";
        if (!$this->db-exec($sql)) {
            throw new \Exception("Couldn't update database");
        }
    }

    public function removeUser(IUser $user)
    {
        $userId = $user->getId();
        $sql = "DELETE FROM User WHERE Id = $userId; " .
               "DELETE FROM Following WHERE FollowerId = $userId OR FollowedId = $userId; " .
               "DELETE FROM Comment WHERE UserId = $userId; " .
               "DELETE FROM Watching WHERE UserId = $userId";

        if (!$this->db-exec($sql)) {
            throw new \Exception("Couldn't update database");
        }
    }
    //endregion

    //region Following
    public function addFriend(\_Interfaces\IUser $followerUser, \_Interfaces\IUser $followedUser) {
        $followerId = $followerUser->getId();
        $followedId = $followedUser->getId();

        $sql = "INSERT INTO Following (FollowerId, FollowedId) VALUES ($followerId, $followedId)";
        if (!$this->db-exec($sql)) {
            throw new \Exception("Couldn't update database");
        }
    }

    public function removeFriend(\_Interfaces\IUser $followerUser, \_Interfaces\IUser $followedUser) {
        $followerId = $followerUser->getId();
        $followedId = $followedUser->getId();

        $sql = "DELETE FROM Following WHERE FollowerId = $followerId AND FollowedId = $followedId)";
        if (!$this->db-exec($sql)) {
            throw new \Exception("Couldn't update database");
        }
    }
    //endregion

    //region Comments
    public function getComments(\_Interfaces\IShow $show): array {
        $showId = $show->getId();
        $sql = "SELECT * FROM Comment WHERE ShowId = $showId";
        $query = $this->db->query($sql);

        if (!$query) {
            throw new \Exception("Couldn't get values from database");
        }

        $result = array();

        while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
            $result[] = new \Comment($row['Id'], $this->getUserById($row['UserId']), $row['Content'], $row['DateTime']);
        }

        return $result;
    }

    public function addComment(\_Interfaces\IUser $user, \_Interfaces\IShow $show, string $content)
    {
        $userId = $user->getId();
        $showId = $show->getId();

        $sql = "INSERT INTO Comment (UserId, ShowId, Content) VALUES ($userId, $showId, $content)";
        if (!$this->db-exec($sql)) {
            throw new \Exception("Couldn't update database");
        }
    }

    public function removeComment(\_Interfaces\IComment $comment)
    {
        $commentId = $comment->getId();

        $sql = "DELETE FROM Comment WHERE Id = $commentId)";
        if (!$this->db-exec($sql)) {
            throw new \Exception("Couldn't update database");
        }
    }
    //endregion

    //region Watching
    public function getWatching(\_Interfaces\IShow $show): array {
        $showId = $show->getId();
        $sql = "SELECT * FROM Watching WHERE ShowId = $showId";
        $query = $this->db->query($sql);

        if (!$query) {
            throw new \Exception("Couldn't get values from database");
        }

        $result = array();

        while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
            $result[] = new \ShowStatus($this, $this->getUserById($row['UserId']), $row['Episodes'], $row['Rating']);
        }

        return $result;
    }

    public function addWatching(\_Interfaces\IUser $user, IShow $show) {
        $userId = $user->getId();
        $showId = $show->getId();

        $sql = "INSERT INTO Watching (UserId, ShowId) VALUES ($userId, $showId)";
        if (!$this->db-exec($sql)) {
            throw new \Exception("Couldn't update database");
        }
    }

    public function updateWatching(\_Interfaces\IUser $user, \_Interfaces\IShow $show, ?int $episodesWatched, ?int $rating)
    {
        $userId = $user->getId();
        $showId = $show->getId();

        $sql = "UPDATE Watching SET Episodes = $episodesWatched, Rating = $rating  WHERE UserId = $userId AND ShowId = $showId";
        if (!$this->db-exec($sql)) {
            throw new \Exception("Couldn't update database");
        }
    }

    public function removeWatching(\_Interfaces\IUser $user, \_Interfaces\IShow $show)
    {
        $userId = $user->getId();
        $showId = $show->getId();

        $sql = "DELETE FROM Watching WHERE UserId = $userId AND ShowId = $showId)";
        if (!$this->db-exec($sql)) {
            throw new \Exception("Couldn't update database");
        }
    }
    //endregion

    //region Private Methods
    private function getUsers(string $sql) : array {
        $query = $this->db->query($sql);

        if (!$query) {
            throw new \Exception("Couldn't get values from database");
        }

        $result = array();

        while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
            $result[] = new \User($this, $row['Id'], $row['Username'], $row['Password'], $row['Email'],
                $row['ProfilePicturePath'], $row['IsAdmin'] === 1, $row['CanComment'] === 1);
        }

        return $result;
    }
    //endregion
}