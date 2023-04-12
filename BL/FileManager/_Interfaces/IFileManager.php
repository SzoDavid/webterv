<?php

namespace BL\FileManager\_Interfaces;

use BL\_enums\EFileCategories;

interface IFileManager
{
    /**
     * Uploads file and returns with path.
     * @param array $file
     * @param EFileCategories $category
     * @return string
     */
    public function upload(array $file, EFileCategories $category): string;
}