<?php

namespace BL\ConfigLoader\_Interfaces;

use BL\DTO\_Interfaces\IUser;

interface IConfigLoader
{
    public function getDataSourceConfigs(): IDataSourceConfigs;
    public function getCoverDir(): string;
    public function getTrailerDir(): string;
    public function getOstDir(): string;
    public function getPfpDir(): string;
    public function isAdminGenerationEnabled(): bool;
    public function getDefaultAdminUser(): ?IUser;
}