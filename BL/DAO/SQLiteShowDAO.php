<?php

namespace BL\DAO;

use BL\DataSource\SQLiteDataSource;
use BL\DTO\_Interfaces\IShow;
use BL\DTO\_Interfaces\IUser;
use BL\DTO\Show;
use Exception;

class SQLiteShowDAO implements _Interfaces\IShowDAO
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
            throw new Exception('Could not get shows', 0, $exception);
        }
    }

    /**
     * @inheritDoc
     */
    public function getBySearchText(string $searchText): array
    {
        if (empty(trim($searchText))) $searchText = '%';

        $query = $this->dataSource->getDB()->query("SELECT * FROM Show WHERE Title LIKE '%$searchText%'");

        if (!$query) {
            throw new Exception('Could not get values from database: ' . $this->dataSource->getDB()->lastErrorMsg());
        }

        $result = array();

        while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
            $result[] = new Show($row['Id'], $row['Title'], $row['NumEpisodes'], $row['Description'],
                $row['CoverPath'], $row['TrailerPath'], $row['OstPath']);
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id): ?IShow
    {
        $query = $this->dataSource->getDB()->query("SELECT * FROM Show WHERE Id = '$id'");

        if (!$query) {
            throw new Exception('Could not get values from database: ' . $this->dataSource->getDB()->lastErrorMsg());
        }

        if ($row = $query->fetchArray(SQLITE3_ASSOC)) {
            return new Show($row['Id'], $row['Title'], $row['NumEpisodes'], $row['Description'],
                $row['CoverPath'], $row['TrailerPath'], $row['OstPath']);
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function getByUser(IUser $user): array
    {
        $id = $user->getId();

        $query = $this->dataSource->getDB()->query("SELECT * FROM Show WHERE Show.Id IN (SELECT ShowId FROM Watching WHERE UserId = '$id')");

        if (!$query) {
            throw new Exception('Could not get values from database: ' . $this->dataSource->getDB()->lastErrorMsg());
        }

        $result = array();

        while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
            $result[] = new Show($row['Id'], $row['Title'], $row['NumEpisodes'], $row['Description'],
                $row['CoverPath'], $row['TrailerPath'], $row['OstPath']);
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function getFriendsShowsByUser(IUser $user): array
    {
        $id = $user->getId();

        $query = $this->dataSource->getDB()->query("SELECT * FROM Show WHERE Show.Id IN ("
            . "SELECT Watching.ShowId FROM Watching, Following, User WHERE Following.FollowedId=Watching.UserId AND User.Id=Following.FollowedId AND Following.FollowerId='$id' AND Watching.ShowId NOT IN ("
            . "SELECT ShowId FROM Watching WHERE UserId='$id') AND (User.Visibility=2 OR (User.Visibility=1 AND Following.FollowedId IN "
            . "(SELECT Following.FollowerId FROM Following WHERE Following.FollowedId='$id'))))");

        if (!$query) {
            throw new Exception('Could not get values from database: ' . $this->dataSource->getDB()->lastErrorMsg());
        }

        $result = array();

        while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
            $result[] = new Show($row['Id'], $row['Title'], $row['NumEpisodes'], $row['Description'],
                $row['CoverPath'], $row['TrailerPath'], $row['OstPath']);
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function save(IShow $show): int
    {
        $showId = $show->getId();
        $title = $show->getTitle();
        $numEpisodes = $show->getNumEpisodes();
        $coverPath = $show->getCoverPath();
        $trailerPath = $show->getTrailerPath();
        $ostPath = $show->getOstPath();
        $description = $show->getDescription();

        if ($showId == null) {
            $sql = "INSERT INTO Show (Title, NumEpisodes, Description, CoverPath, TrailerPath, OstPath) VALUES ('$title', '$numEpisodes', '$description', '$coverPath', '$trailerPath', '$ostPath')";
        } else {
            try {
                $oldShow = $this->getById($showId);
                if ($oldShow->getOstPath() && $show->getOstPath() != $oldShow->getOstPath()) unlink('../../' . $oldShow->getOstPath());
                if ($oldShow->getTrailerPath() && $show->getTrailerPath() != $oldShow->getTrailerPath()) unlink('../../' . $oldShow->getTrailerPath());
                if ($oldShow->getCoverPath() && $show->getCoverPath() != $oldShow->getCoverPath()) unlink('../../' . $oldShow->getCoverPath());
            } catch (Exception $ex) {
                throw  new Exception('Failed to get old data from database', 0, $ex);
            }
            $sql = "UPDATE Show SET Title = '$title', NumEpisodes = '$numEpisodes', Description = '$description', CoverPath = '$coverPath', TrailerPath = '$trailerPath', OstPath = '$ostPath' WHERE Id = '$showId'";
        }
        if (!$this->dataSource->getDB()->exec($sql)) {
            throw new Exception('Could not update database: ' . $this->dataSource->getDB()->lastErrorMsg());
        }

        return $showId ?? $this->dataSource->getDB()->lastInsertRowID();
    }

    /**
     * @inheritDoc
     */
    public function remove(IShow $show): void
    {
        $showId = $show->getId();
        $sql = "DELETE FROM Show WHERE Id = '$showId'; " .
            "DELETE FROM Comment WHERE ShowId = '$showId'; " .
            "DELETE FROM Watching WHERE ShowId = '$showId'";

        if (!$this->dataSource->getDB()->exec($sql)) {
            throw new Exception('Could not update database: ' . $this->dataSource->getDB()->lastErrorMsg());
        }

        if ($show->getOstPath()) unlink('../../' . $show->getOstPath());
        if ($show->getTrailerPath()) unlink('../../' . $show->getTrailerPath());
        if ($show->getCoverPath()) unlink('../../' . $show->getCoverPath());
    }
    //endregion
}