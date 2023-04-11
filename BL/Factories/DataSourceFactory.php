<?php

namespace BL\Factories;

use BL\ConfigLoader\_Interfaces\IConfigLoader;
use BL\ConfigLoader\_Interfaces\IDataSourceConfigs;
use BL\DataSource\_Interfaces\IDataSource;
use BL\DataSource\SQLiteDataSource;
use Exception;

class DataSourceFactory implements _Interfaces\IDataSourceFactory
{
    //region Properties
    private IDataSourceConfigs $configs;
    //endregion

    //region Constructor
    public function __construct(IConfigLoader $configLoader)
    {
        $this->configs = $configLoader->getDataSourceConfigs();
    }
    //endregion

    //region Getters
    /**
     * @inheritDoc
     */
    public function createDataSource(): IDataSource
    {
        try {
            return match ($this->configs->getType()) {
                'sqlite' => new SQLiteDataSource($this->configs->getPath()),
                default => throw new Exception('Invalid data source type: ' . $this->configs->getType())
            };
        } catch (Exception $ex) {
            throw new Exception('Could not create data source', 0, $ex);
        }
    }
    //endregion
}