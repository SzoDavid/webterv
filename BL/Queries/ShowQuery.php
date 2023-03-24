<?php

namespace BL\Queries;

use BL\DataSource\_Interfaces\IDataSource;
use BL\_Interfaces\IShow;
use BL\Queries\_Interfaces\IShowQuery;
use Exception;

class ShowQuery implements IShowQuery
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
    public function getAllShows(): array
    {
        try {
            return self::getShowsBySearchText('%');
        } catch (Exception $exception) {
            throw new Exception('Could not get shows', 7, $exception);
        }
    }

    public function getShowsBySearchText(string $searchText): array
    {
        // TODO: validate search text
        try {
            return $this->dataSource->getShowsBySearchText($searchText);
        } catch (Exception $exception) {
            throw new Exception('Could not get shows', 7, $exception);
        }
    }

    public function getShowById(int $id): IShow
    {
        try {
            return $this->dataSource->getShowById($id);
        } catch (Exception $exception) {
            throw new Exception('Could not get show', 7, $exception);
        }
    }
    //endregion
}