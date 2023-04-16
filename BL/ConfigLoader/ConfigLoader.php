<?php

namespace BL\ConfigLoader;

use BL\ConfigLoader\_Interfaces\IDataSourceConfigs;
use BL\DTO\_Interfaces\IUser;
use BL\DTO\User;
use Exception;

class ConfigLoader implements _Interfaces\IConfigLoader
{
    //region Properties
    private IDataSourceConfigs $dataSourceConfigs;
    private string $coverDir;
    private string $trailerDir;
    private string $ostDir;
    private string $pfpDir;
    private bool $isAdminGenerationEnabled;
    private ?IUser $defaultAdminUser;
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
            $this->coverDir = $raw_config['resource_directories']['cover_dir'];
            $this->trailerDir = $raw_config['resource_directories']['trailer_dir'];
            $this->ostDir = $raw_config['resource_directories']['ost_dir'];
            $this->pfpDir = $raw_config['resource_directories']['pfp_dir'];

            $this->isAdminGenerationEnabled = $raw_config['default_admin_user']['generate_admin_user'];

            if ($this->isAdminGenerationEnabled) {
                $this->defaultAdminUser = User::createNewUser(
                    $raw_config['default_admin_user']['username'],
                    password_hash($raw_config['default_admin_user']['password'], PASSWORD_DEFAULT),
                    $raw_config['default_admin_user']['email'],
                    true
                );
            }
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

    public function isAdminGenerationEnabled(): bool
    {
        return $this->isAdminGenerationEnabled;
    }

    public function getDefaultAdminUser(): ?IUser
    {
        return $this->defaultAdminUser;
    }
    //endregion
}