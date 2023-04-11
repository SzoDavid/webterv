<?php

namespace BL\Factories\_Interfaces;

use BL\DataSource\_Interfaces\IDataSource;
use Exception;

interface IDataSourceFactory
{
    /**
     * Creates a datasource object with the correct type
     * @throws Exception
     */
    public function createDataSource(): IDataSource;
}