<?php

namespace BL\DAO;

use BL\DAO\_Interfaces\IShowDAO;
use BL\DAO\_Interfaces\IUserDAO;
use BL\DataSource\SQLiteDataSource;
use BL\DTO\_Interfaces\IRating;
use BL\DTO\_Interfaces\IShow;
use BL\DTO\_Interfaces\IUser;
use BL\DTO\Rating;
use Exception;

class SQLiteRatingDAO implements _Interfaces\IRatingDAO
{
    //region Properties
    private SQLiteDataSource $dataSource;
    private IUserDAO $userDAO;
    private IShowDAO $showDAO;
    //endregion

    //region Constructor
    public function __construct(SQLiteDataSource $dataSource, IUserDAO $userDAO, IShowDAO $showDAO)
    {
        $this->dataSource = $dataSource;
        $this->userDAO = $userDAO;
        $this->showDAO = $showDAO;
    }
    //endregion

    /**
     * @inheritDoc
     */
    public function getByShow(IShow $show): array
    {
        $id = $show->getId();

        $sql = "SELECT * FROM Watching WHERE ShowId = '$id'";
        $query = $this->dataSource->getDB()->query($sql);

        if (!$query) {
            throw new Exception('Could not get values from database: ' . $this->dataSource->getDB()->lastErrorMsg());
        }

        $result = array();

        while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
            try {
                $user = $this->userDAO->getById($row['UserId']);
            } catch (Exception $exception) {
                throw new Exception('Failed to get user', 0, $exception);
            }

            if ($user) {
                $result[] = new Rating($show, $user, $row['Episodes'], ($row['Rating'] == '') ? null : intval($row['Rating']));
            }
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function getByUser(IUser $user): array
    {
        $id = $user->getId();

        $sql = "SELECT * FROM Watching WHERE UserId = '$id'";
        $query = $this->dataSource->getDB()->query($sql);

        if (!$query) {
            throw new Exception('Could not get values from database: ' . $this->dataSource->getDB()->lastErrorMsg());
        }

        $result = array();

        while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
            try {
                $show = $this->showDAO->getById($row['ShowId']);
            } catch (Exception $exception) {
                throw new Exception('Failed to get show', 0, $exception);
            }

            if ($show) {
                $result[] = new Rating($show, $user, $row['Episodes'], ($row['Rating'] == '') ? null : intval($row['Rating']));
            }
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function getByShowAndUser(IShow $show, IUser $user): ?IRating
    {
        $showId = $show->getId();
        $userId = $user->getId();

        $sql = "SELECT * FROM Watching WHERE ShowId = '$showId' AND UserId = '$userId'";
        $query = $this->dataSource->getDB()->query($sql);

        if (!$query) {
            throw new Exception('Could not get values from database: ' . $this->dataSource->getDB()->lastErrorMsg());
        }

        if ($row = $query->fetchArray(SQLITE3_ASSOC)) {
            return new Rating($show, $user, $row['Episodes'], ($row['Rating'] == '') ? null : intval($row['Rating']));
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function getAverageRatingByShow(IShow $show): float
    {
        $ratings = $this->getByShow($show);

        $sum = 0;
        $count = 0;

        /* @var $rating IRating */
        foreach ($ratings as $rating) {
            if ($rating->getRating()) {
                $sum += $rating->getRating();
                $count++;
            }
        }

        return ($count != 0) ? (float) $sum / $count : 0;
    }

    /**
     * @inheritDoc
     */
    public function getUnwatchedByUser(IUser $user): array
    {
        $id = $user->getId();

        $sql = "SELECT Watching.* FROM Watching, Show WHERE Watching.ShowId=Show.Id AND Watching.UserId = '$id' AND Watching.Episodes<Show.NumEpisodes";
        $query = $this->dataSource->getDB()->query($sql);

        if (!$query) {
            throw new Exception('Could not get values from database: ' . $this->dataSource->getDB()->lastErrorMsg());
        }

        $result = array();

        while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
            try {
                $show = $this->showDAO->getById($row['ShowId']);
            } catch (Exception $exception) {
                throw new Exception('Failed to get show', 0, $exception);
            }

            if ($show) {
                $result[] = new Rating($show, $user, $row['Episodes'], ($row['Rating'] == '') ? null : intval($row['Rating']));
            }
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function save(IRating $rating): void
    {
        $userId = $rating->getUser()->getId();
        $showId = $rating->getShow()->getId();
        $ratingValue = $rating->getRating();
        $episodes = $rating->getEpisodesWatched();

        $sql = "INSERT OR REPLACE INTO Watching (UserId, ShowId, Episodes, Rating) VALUES ('$userId', '$showId', '$episodes', '$ratingValue')";
        if (!$this->dataSource->getDB()->exec($sql)) {
            throw new Exception('Could not update database: ' . $this->dataSource->getDB()->lastErrorMsg());
        }
    }

    /**
     * @inheritDoc
     */
    public function remove(IRating $rating): void
    {
        $userId = $rating->getUser()->getId();
        $showId = $rating->getShow()->getId();

        $sql = "DELETE FROM Watching WHERE UserId = '$userId' AND ShowId = '$showId'";
        if (!$this->dataSource->getDB()->exec($sql)) {
            throw new Exception('Could not update database: ' . $this->dataSource->getDB()->lastErrorMsg());
        }
    }
}