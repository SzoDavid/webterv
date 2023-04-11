<?php

namespace BL\ConfigLoader;

use BL\ConfigLoader\_Interfaces\IDataSourceConfigs;
use Exception;

class ConfigLoader implements _Interfaces\IConfigLoader
{
    //region Properties
    private IDataSourceConfigs $dataSourceConfigs;
    //endregion

    //region Constructor
    /**
     * @throws Exception
     */
    public function __construct(string $configPath='/Resources/config.json')
    {
        try {
            $raw_config = json_decode(file_get_contents($configPath), true);

            $this->dataSourceConfigs = match ($raw_config['data_source']['type']) {
                'sqlite' => new SQLiteConfigs($raw_config['data_source']),
                default => throw new Exception('Unknown data source type: ' . $raw_config['data_source']['type']),
            };
        } catch (Exception $ex) {
            throw new Exception('Could not parse configs', 0, $ex);
        }
    }
    //endregion

    //region Getters
    public function getDataSourceConfigs(): IDataSourceConfigs
    {
        return $this->dataSourceConfigs;
    }
    //endregion
}