<?php

namespace BL\FileManager;

use BL\_enums\EFileCategories;
use BL\ConfigLoader\_Interfaces\IConfigLoader;
use Exception;

class FileManager implements _Interfaces\IFileManager
{
    //region Properties
    private IConfigLoader $configs;
    //endregion

    //region Constructor
    /**
     * @param IConfigLoader $configs
     */
    public function __construct(IConfigLoader $configs)
    {
        $this->configs = $configs;
    }
    //endregion

    //region Public Members
    /**
     * @inheritDoc
     * @throws Exception
     */
    public function upload(array $file, EFileCategories $category): string
    {
        $target_dir = match ($category) {
            EFileCategories::Cover => $this->configs->getCoverDir(),
            EFileCategories::Trailer => $this->configs->getTrailerDir(),
            EFileCategories::Ost => $this->configs->getOstDir(),
            EFileCategories::Pfp => $this->configs->getPfpDir(),
        };
        $fileType = strtolower(pathinfo($file["tmp_name"],PATHINFO_EXTENSION));

        do {
            $target_file = $target_dir . sprintf("%06d", mt_rand(1, 999999)) . '.' . $fileType;
        } while (file_exists($target_file));

        if (($category === EFileCategories::Cover || $category === EFileCategories::Pfp)
            && !getimagesize($file["tmp_name"])) {
            throw new Exception('Invalid image file: ' . basename($file["name"]));
        }

        if ($file["size"] > 500000) {
            throw new Exception('File size is too big: ' . $file["size"]);
        }

        //TODO: validate file type

        if (!move_uploaded_file($file["tmp_name"], $target_file)) {
            throw new Exception('Could not upload file');
        }

        return $target_file;
    }
    //endregion
}