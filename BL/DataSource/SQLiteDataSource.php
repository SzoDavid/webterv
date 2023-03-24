<?php

namespace BL\DataSource;

use BL\Comment;
use BL\_Interfaces\IComment;
use BL\_Interfaces\IRating;
use BL\_Interfaces\IShow;
use BL\_Interfaces\IUser;
use BL\DataSource\_Interfaces\IDataSource;
use BL\Rating;
use BL\Show;
use BL\User;
use Exception;
use SQLite3;

class SQLiteDataSource implements IDataSource
{
    //region Properties
    private SQLite3 $db;
    //endregion

    //region Constructor
    /**
     * Creates an SQLite Data Source to manage website data
     * @throws Exception - when couldn't connect to database
     */
    function __construct(string $dbpath) {
        try {
            // TODO: create db if not exists
            $this->db = new SQLite3($dbpath, SQLITE3_OPEN_READWRITE);
        } catch (Exception $exception) {
            throw new Exception('Could not open database', 1, $exception);
        }
    }
    //endregion

    //region User Query
    public function getUsersBySearchText(string $searchText): array
    {
        $query = $this->db->query("SELECT * FROM User WHERE Username LIKE '$searchText'");

        if (!$query) {
            throw new Exception('Could not get values from database: ' . $this->db->lastExtendedErrorCode(), 1);
        }

        $result = array();

        while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
            $result[] = new User($this, $row['Id'], $row['Username'], $row['Password'], $row['Email'],
                $row['ProfilePicturePath'], $row['IsAdmin'] === 1, $row['CanComment'] === 1, [], []);
        }

        return $result;
    }

    public function getUserById(int $id): IUser
    {
        $query = $this->db->query("SELECT * FROM User WHERE Id = '$id'");

        if (!$query) {
            throw new Exception('Could not get values from database: ' . $this->db->lastExtendedErrorCode(), 1);
        }

        $row = $query->fetchArray(SQLITE3_ASSOC);

        try {
            $friends = $this->getFriendsByFollowerId($id);
        } catch (Exception $exception) {
            // TODO: Log warnings?
        }

        try {
            $shows = $this->getShowsByUserId($id);
        } catch (Exception $exception) {
            // TODO: Log warnings?
        }

        return new User($this, $row['Id'], $row['Username'], $row['Password'], $row['Email'],
            $row['ProfilePicturePath'], $row['IsAdmin'] === 1, $row['CanComment'] === 1,
            $friends ?? [], $shows ?? []);
    }

    public function getUserByEmail(string $email): IUser
    {
        $query = $this->db->query("SELECT * FROM User WHERE Email = '$email'");

        if (!$query) {
            throw new Exception('Could not get values from database: ' . $this->db->lastExtendedErrorCode(), 1);
        }

        $row = $query->fetchArray(SQLITE3_ASSOC);

        return new User($this, $row['Id'], $row['Username'], $row['Password'], $row['Email'],
            $row['ProfilePicturePath'], $row['IsAdmin'] === 1, $row['CanComment'] === 1, [], []);
    }
    //endregion

    //region User
    public function saveUser(IUser $user): void
    {
        $userId = $user->getId();
        $username = $user->getUsername();
        $email = $user->getEmail();
        $password = $user->getPasswordHash();
        $pfpPath = $user->getProfilePicturePath();
        $admin = $user->isAdmin() ? 1 : 0;
        $canComment = $user->isMuted() ? 1 : 0;

        if ($userId === null) {
            $sql = "INSERT INTO User (Username, Email, Password) VALUES ('$username', '$email', '$password')";
        } else {
            $sql = "UPDATE User SET Username = '$username', Email = '$email', Password = '$password', ProfilePicturePath = '$pfpPath', IsAdmin = '$admin', CanComment = '$canComment' WHERE Id = '$userId'";
        }
        if (!$this->db->exec($sql)) {
            throw new Exception('Could not update database ' . $this->db->lastExtendedErrorCode(), 1);
        }
    }

    public function removeUser(IUser $user): void
    {
        $userId = $user->getId();
        $sql = "DELETE FROM User WHERE Id = '$userId'; " .
            "DELETE FROM Following WHERE FollowerId = '$userId' OR FollowedId = '$userId'; " .
            "DELETE FROM Comment WHERE UserId = '$userId'; " .
            "DELETE FROM Watching WHERE UserId = '$userId'";

        if (!$this->db->exec($sql)) {
            throw new Exception('Could not update database: ' . $this->db->lastExtendedErrorCode(), 1);
        }
    }

    public function addFriend(IUser $followerUser, IUser $followedUser): void
    {
        $followerId = $followerUser->getId();
        $followedId = $followedUser->getId();

        $sql = "INSERT INTO Following (FollowerId, FollowedId) VALUES ('$followerId', '$followedId')";
        if (!$this->db->exec($sql)) {
            throw new Exception('Could not update database: ' . $this->db->lastExtendedErrorCode(), 1);
        }
    }

    public function removeFriend(IUser $followerUser, IUser $followedUser): void
    {
        $followerId = $followerUser->getId();
        $followedId = $followedUser->getId();

        $sql = "DELETE FROM Following WHERE FollowerId = '$followerId' AND FollowedId = '$followedId'";
        if (!$this->db->exec($sql)) {
            throw new Exception('Could not update database: ' . $this->db->lastExtendedErrorCode(), 1);
        }
    }
    //endregion

    //region Show Query
    public function getShowsBySearchText(string $searchText): array
    {
        $query = $this->db->query("SELECT * FROM Show WHERE Title LIKE '$searchText'");

        if (!$query) {
            throw new Exception("Couldn't get values from database");
        }

        $result = array();

        while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
            try {
                $ratings = $this->getRatingsByShowId($row['Id']);
            } catch (Exception $exception) {
                // TODO: Log warnings?
            }

            $result[] = new Show($this, $row['Id'], $row['Title'], $row['NumEpisodes'], $row['Description'],
                $row['CoverPath'], $row['TrailerPath'], $row['OstPath'], [], $ratings ?? []);
        }

        return $result;
    }

    public function getShowById(int $id): IShow
    {
        $query = $this->db->query("SELECT * FROM Show WHERE Id = '$id'");

        if (!$query) {
            throw new Exception('Could not get values from database: ' . $this->db->lastExtendedErrorCode(), 1);
        }

        $row = $query->fetchArray(SQLITE3_ASSOC);

        try {
            $ratings = $this->getRatingsByShowId($id);
        } catch (Exception $exception) {
            // TODO: Log warnings?
        }

        try {
            $comments = $this->getCommentByShowId($row['Id']);
        } catch (Exception $exception) {
            // TODO: Log warnings?
        }

        return new Show($this, $row['Id'], $row['Title'], $row['numEpisodes'], $row['Description'],
            $row['CoverPath'], $row['TrailerPath'], $row['OstPath'],
            $comments ?? [], $ratings ?? []);
    }
    //endregion

    //region Show
    public function saveShow(IShow $show): void
    {
        $showId = $show->getId();
        $title = $show->getTitle();
        $numEpisodes = $show->getNumEpisodes();
        $coverPath = $show->getCoverPath();
        $trailerPath = $show->getTrailerPath();
        $ostPath = $show->getOstPath();
        $description = $show->getDescription();

        if ($showId === null) {
            $sql = "INSERT INTO Show (Title, NumEpisodes, Description, CoverPath, TrailerPath, OstPath) VALUES ('$title', '$numEpisodes', '$description', '$coverPath', '$trailerPath', '$ostPath')";
        } else {
            $sql = "UPDATE Show SET Title = '$title', NumEpisodes = '$numEpisodes', Description = '$description', CoverPath = '$coverPath', TrailerPath = '$trailerPath', OstPath = '$ostPath' WHERE Id = '$showId'";
        }
        if (!$this->db->exec($sql)) {
            throw new Exception('Could not update database: ' . $this->db->lastExtendedErrorCode(), 1);
        }
    }

    public function removeShow(IShow $show): void
    {
        $showId = $show->getId();
        $sql = "DELETE FROM Show WHERE Id = '$showId'; " .
            "DELETE FROM Comment WHERE ShowId = '$showId'; " .
            "DELETE FROM Watching WHERE ShowId = '$showId'";

        if (!$this->db->exec($sql)) {
            throw new Exception('Could not update database: ' . $this->db->lastExtendedErrorCode(),1);
        }
    }
    //endregion

    //region Comment
    public function saveComment(IComment $comment): void
    {
        $userId = $comment->getAuthor()->getId();
        $showId = $comment->getShow()->getId();
        $content = $comment->getContent();

        $sql = "INSERT INTO Comment (UserId, ShowId, Content) VALUES ('$userId', '$showId', '$content')";
        if (!$this->db->exec($sql)) {
            throw new Exception('Could not update database: ' . $this->db->lastExtendedErrorCode(), 1);
        }
    }

    public function removeComment(IComment $comment): void
    {
        $commentId = $comment->getId();

        $sql = "DELETE FROM Comment WHERE Id = '$commentId'";
        if (!$this->db->exec($sql)) {
            throw new Exception('Could not update database: ' . $this->db->lastExtendedErrorCode(), 1);
        }
    }
    //endregion

    //region Rating
    public function saveRating(IRating $rating): void
    {
        $userId = $rating->getUser()->getId();
        $showId = $rating->getShow()->getId();
        $ratingValue = $rating->getRating();
        $episodes = $rating->getEpisodesWatched();

        $sql = "INSERT OR REPLACE INTO Watching (UserId, ShowId, Episodes, Rating) VALUES ('$userId', '$showId', '$episodes', '$ratingValue')";
        if (!$this->db->exec($sql)) {
            throw new Exception('Could not update database: ' . $this->db->lastExtendedErrorCode(), 1);
        }
    }

    public function removeRating(IRating $rating): void
    {
        $userId = $rating->getUser()->getId();
        $showId = $rating->getShow()->getId();

        $sql = "DELETE FROM Watching WHERE UserId = '$userId' AND ShowId = '$showId'";
        if (!$this->db->exec($sql)) {
            throw new Exception('Could not update database: ' . $this->db->lastExtendedErrorCode(), 1);
        }
    }
    //endregion

    //region Private Methods
    private function getFriendsByFollowerId(int $id): array
    {
        $query = $this->db->query("SELECT * FROM User WHERE User.Id IN (SELECT FollowedId FROM Following WHERE FollowerId = '$id')");

        if (!$query) {
            throw new Exception('Could not get values from database: ' . $this->db->lastExtendedErrorCode(), 1);
        }

        $result = array();

        while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
            $result[] = new User($this, $row['Id'], $row['Username'], $row['Password'], $row['Email'],
                $row['ProfilePicturePath'], $row['IsAdmin'] === 1, $row['CanComment'] === 1, [], []);
        }

        return $result;
    }

    private function getShowsByUserId(int $id): array
    {
        $query = $this->db->query("SELECT * FROM Shows WHERE Show.Id IN (SELECT ShowId FROM Watching WHERE UserId = '$id')");

        if (!$query) {
            throw new Exception('Could not get values from database: ' . $this->db->lastExtendedErrorCode(), 1);
        }

        $result = array();

        while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
            $result[] = new Show($this, $row['Id'], $row['Title'], $row['NumEpisodes'], $row['Description'],
                $row['CoverPath'], $row['TrailerPath'], $row['OstPath'], [], []);
        }

        return $result;
    }

    private function getRatingsByShowId(int $id): array
    {
        $sql = "SELECT * FROM Watching WHERE ShowId = '$id'";
        $query = $this->db->query($sql);

        if (!$query) {
            throw new Exception('Could not get values from database: ' . $this->db->lastExtendedErrorCode(), 1);
        }

        $result = array();

        while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
            try {
                $user = $this->getUserById($row['UserId']);
            } catch (Exception $exception) {
                throw new Exception('Could not get ratings, because the author was removed from the database', 1, $exception);
            }

            $result[] = new Rating($this, null, $user, $row['Episodes'], $row['Rating']);
        }

        return $result;
    }

    private function getCommentByShowId(int $id): array
    {
        $sql = "SELECT * FROM Comment WHERE ShowId = '$id'";
        $query = $this->db->query($sql);

        if (!$query) {
            throw new Exception('Could not get values from database: ' . $this->db->lastExtendedErrorCode(), 1);
        }

        $result = array();

        while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
            try {
                $user = $this->getUserById($row['UserId']);
            } catch (Exception $exception) {
                throw new Exception('Could not get comments, because the author was removed from the database', 1, $exception);
            }

            $result[] = new Comment($this, $row['Id'], null, $user, $row['content'], $row['time']);
        }

        return $result;
    }
    //endregion
}