<?php

namespace BL\Queries;

use BL\DataSource\_Interfaces\IDataSource;
use BL\_Interfaces\IUser;
use BL\Queries\_Interfaces\IUserQuery;
use Exception;

class UserQuery implements IUserQuery
{
    //region Properties
    private IDataSource $dataSource;
    //endregion

    //region Constructor
    public function __construct(IDataSource $dataSource)
    {
        $this->dataSource = $dataSource;
    }
    //endregion

    //region Queries
    public function getAllUsers(): array
    {
        try {
            return self::getUsersBySearchText('%');
        } catch (Exception $exception) {
            throw new Exception('Could not get users', 6, $exception);
        }
    }

    public function getUsersBySearchText(string $searchText): array
    {
        // TODO: validate search text
        try {
            return $this->dataSource->getUsersBySearchText($searchText);
        } catch (Exception $exception) {
            throw new Exception('Could not get users', 6, $exception);
        }
    }

    public function getUserById(int $id): IUser
    {
        try {
            return $this->dataSource->getUserById($id);
        } catch (Exception $exception) {
            throw new Exception('Could not get user', 6, $exception);
        }
    }

    public function getUserByEmail(string $email): IUser
    {
        try {
            return $this->dataSource->getUserByEmail($email);
        } catch (Exception $exception) {
            throw new Exception('Could not get user', 6, $exception);
        }
    }
    //endregion
}