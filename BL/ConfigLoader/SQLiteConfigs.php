<?php

namespace BL\ConfigLoader;

use BL\_enums\EDataSourceTypes;
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
            if ($this->isAbsolutePath($config['path'][0])) {
                $this->path = $config['path'];
            } else {
                $this->path = __DIR__ . '/../../' . $config['path'];
            }
        } catch (Exception $ex) {
            throw new Exception('Could not load data source config', 0, $ex);
        }

    }
    //endregion

    //region Getters
    public function getType(): EDataSourceTypes
    {
        return EDataSourceTypes::SQLite;
    }

    public function getPath(): string
    {
        return $this->path;
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