<?php

namespace BL\ConfigLoader\_Interfaces;

interface IConfigLoader
{
    public function getDataSourceConfigs(): IDataSourceConfigs;
}