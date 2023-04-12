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
            $this->coverDir = ($this->isAbsolutePath($raw_config['cover_dir'])) ? $raw_config['cover_dir'] : __DIR__ . '/../../' . $raw_config['cover_dir'];
            $this->trailerDir = ($this->isAbsolutePath($raw_config['trailer_dir'])) ? $raw_config['trailer_dir'] : __DIR__ . '/../../' . $raw_config['trailer_dir'];
            $this->ostDir = ($this->isAbsolutePath($raw_config['ost_dir'])) ? $raw_config['ost_dir'] : __DIR__ . '/../../' . $raw_config['ost_dir'];
            $this->pfpDir = ($this->isAbsolutePath($raw_config['pfp_dir'])) ? $raw_config['pfp_dir'] : __DIR__ . '/../../' . $raw_config['pfp_dir'];

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

    //region Private Members
    /**
     * @throws Exception
     */
    private function isAbsolutePath(string $path): bool {
        if (!ctype_print($path)) {
            throw new Exception('Path can NOT have non-printable characters or be empty');
        }
        // Optional wrapper(s).
        $regExp = '%^(?<wrappers>(?:[[:print:]]{2,}://)*)';
        // Optional root prefix.
        $regExp .= '(?<root>(?:[[:alpha:]]:[/\\\\]|/)?)';
        // Actual path.
        $regExp .= '(?<path>(?:[[:print:]]*))$%';
        $parts = [];
        if (!preg_match($regExp, $path, $parts)) {
            throw new Exception('Path is NOT valid, was given ' . $path);
        }
        if ('' !== $parts['root']) {
            return true;
        }
        return false;
    }
    //endregion
}