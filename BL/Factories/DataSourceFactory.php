<?php

namespace BL\Factories;

use BL\_enums\EDataSourceTypes;
use BL\ConfigLoader\_Interfaces\IConfigLoader;
use BL\DataSource\_Interfaces\IDataSource;
use BL\DataSource\SQLiteDataSource;
use Exception;

class DataSourceFactory implements _Interfaces\IDataSourceFactory
{
    //region Properties
    private IConfigLoader $configs;
    //endregion

    //region Constructor
    public function __construct(IConfigLoader $configLoader)
    {
        $this->configs = $configLoader;
    }
    //endregion

    //region Getters
    /**
     * @inheritDoc
     */
    public function createDataSource(): IDataSource
    {
        try {
            return match ($this->configs->getDataSourceConfigs()->getType()) {
                EDataSourceTypes::SQLite => new SQLiteDataSource($this->configs),
            };
        } catch (Exception $ex) {
            throw new Exception('Could not create data source', 0, $ex);
        }
    }
    //endregion
}