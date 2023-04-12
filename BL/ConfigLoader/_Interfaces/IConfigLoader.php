<?php

namespace BL\ConfigLoader\_Interfaces;

interface IConfigLoader
{
    public function getDataSourceConfigs(): IDataSourceConfigs;
    public function getCoverDir(): string;
    public function getTrailerDir(): string;
    public function getOstDir(): string;
    public function getPfpDir(): string;
}