<?php

namespace BL\ConfigLoader;

use BL\ConfigLoader\_Interfaces\IDataSourceConfigs;
use Exception;

class ConfigLoader implements _Interfaces\IConfigLoader
{
    //region Properties
    private IDataSourceConfigs $dataSourceConfigs;
    private string $coverDir;
    private string $trailerDir;
    private string $ostDir;
    private string $pfpDir;
    //endregion

    //region Constructor
    /**
     * @throws Exception
     */
    public function __construct(string $configPath)
    {
        try {
            $raw_config = json_decode(file_get_contents($configPath), true);

            $this->dataSourceConfigs = match ($raw_config['data_source']['type']) {
                'sqlite' => new SQLiteConfigs($raw_config['data_source']),
                default => throw new Exception('Unknown data source type: ' . $raw_config['data_source']['type']),
            };
            $this->coverDir = $raw_config['cover_dir'];
            $this->trailerDir = $raw_config['trailer_dir'];
            $this->ostDir = $raw_config['ost_dir'];
            $this->pfpDir = $raw_config['pfp_dir'];

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

    public function getCoverDir(): string
    {
        return $this->coverDir;
    }

    public function getTrailerDir(): string
    {
        return $this->trailerDir;
    }

    public function getOstDir(): string
    {
        return $this->ostDir;
    }

    public function getPfpDir(): string
    {
        return $this->pfpDir;
    }
    //endregion
}