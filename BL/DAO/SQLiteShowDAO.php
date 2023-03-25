<?php

namespace BL\DAO;

use BL\_Interfaces\IShow;
use BL\_Interfaces\IUser;
use BL\DataSource\SQLiteDataSource;
use BL\Show;
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
        // TODO: validate search text
        $query = $this->dataSource->getDB()->query("SELECT * FROM Show WHERE Title LIKE '$searchText'");

        // TODO: remove code duplication
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
            return new Show($row['Id'], $row['Title'], $row['numEpisodes'], $row['Description'],
                $row['CoverPath'], $row['TrailerPath'], $row['OstPath']);
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function getByUser(IUser $user): array
    {
        // TODO: validate if has id
        $id = $user->getId();

        $query = $this->dataSource->getDB()->query("SELECT * FROM Shows WHERE Show.Id IN (SELECT ShowId FROM Watching WHERE UserId = '$id')");

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
    public function save(IShow $show): void
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
            $sql = "UPDATE Show SET Title = '$title', NumEpisodes = '$numEpisodes', Description = '$description', CoverPath = '$coverPath', TrailerPath = '$trailerPath', OstPath = '$ostPath' WHERE Id = '$showId'";
        }
        if (!$this->dataSource->getDB()->exec($sql)) {
            throw new Exception('Could not update database: ' . $this->dataSource->getDB()->lastErrorMsg());
        }
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
    }
    //endregion
}