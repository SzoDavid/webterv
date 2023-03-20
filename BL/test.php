<?php

require __DIR__ . '/DataSource/SQLiteDataSource.php';

$db = new \DataSource\SQLiteDataSource(realpath('../Data/database.db'));

print_r($db->getUserById(1));
