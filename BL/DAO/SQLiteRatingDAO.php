<?php

namespace BL\DAO;

use BL\_Interfaces\IRating;
use BL\_Interfaces\IShow;
use BL\_Interfaces\IUser;
use BL\DAO\_Interfaces\IUserDAO;
use BL\DataSource\SQLiteDataSource;
use BL\Rating;
use Exception;

class SQLiteRatingDAO implements _Interfaces\IRatingDAO
{
    //region Properties
    private SQLiteDataSource $dataSource;
    private IUserDAO $userDAO;
    //endregion

    //region Constructor
    public function __construct(SQLiteDataSource $dataSource, IUserDAO $userDAO)
    {
        $this->dataSource = $dataSource;
        $this->userDAO = $userDAO;
    }
    //endregion

    /**
     * @inheritDoc
     */
    public function getByShow(IShow $show): array
    {
        // TODO: validate if has id
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
    public function getByShowAndUser(IShow $show, IUser $user): ?IRating
    {
        // TODO: validate if has id
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