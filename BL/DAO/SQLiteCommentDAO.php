<?php

namespace BL\DAO;

use BL\_Interfaces\IComment;
use BL\_Interfaces\IShow;
use BL\Comment;
use BL\DAO\_Interfaces\IUserDAO;
use BL\DataSource\SQLiteDataSource;
use Exception;

class SQLiteCommentDAO implements _Interfaces\ICommentDAO
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

    //region Public Methods
    /**
     * @inheritDoc
     */
    public function getByShow(IShow $show): array
    {
        // TODO: validate if has id
        $id = $show->getId();

        $sql = "SELECT * FROM Comment WHERE ShowId = '$id'";
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
                $result[] = new Comment($row['Id'], null, $user, $row['content'], $row['time']);
            }
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function save(IComment $comment): void
    {
        $userId = $comment->getAuthor()->getId();
        $showId = $comment->getShow()->getId();
        $content = $comment->getContent();

        $sql = "INSERT INTO Comment (UserId, ShowId, Content) VALUES ('$userId', '$showId', '$content')";
        if (!$this->dataSource->getDB()->exec($sql)) {
            throw new Exception('Could not update database: ' . $this->dataSource->getDB()->lastErrorMsg());
        }
    }

    /**
     * @inheritDoc
     */
    public function remove(IComment $comment): void
    {
        $commentId = $comment->getId();

        $sql = "DELETE FROM Comment WHERE Id = '$commentId'";
        if (!$this->dataSource->getDB()->exec($sql)) {
            throw new Exception('Could not update database: ' . $this->dataSource->getDB()->lastErrorMsg());
        }
    }
    //endregion
}