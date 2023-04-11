<?php

namespace BL\ConfigLoader;

use Exception;

class SQLiteConfigs implements _Interfaces\IDataSourceConfigs
{
    //region Properties
    private string $path;
    //endregion

    //region Constructor
    /**
     * @throws Exception
     */
    public function __construct(array $config)
    {
        try {
            $this->path = $config['path'];
        } catch (Exception $ex) {
            throw new Exception('Could not load data source config', 0, $ex);
        }

    }
    //endregion

    //region Getters
    public function getType(): string
    {
        return 'sqlite';
    }

    public function getPath(): string
    {
        return $this->path;
    }
    //endregion
}