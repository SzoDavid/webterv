<?php

namespace BL\ConfigLoader\_Interfaces;

use BL\_enums\EDataSourceTypes;

interface IDataSourceConfigs
{
    public function getType(): EDataSourceTypes;
}