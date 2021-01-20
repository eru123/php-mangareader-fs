<?php

require_once __DIR__."/vendor/autoload.php";

use MangaReaderFS\App;

$config = [
    "dir" => "%USERPROFILE%/Documents/Mangas", // Default Directory of HakuNeko
    "app_name" => "Manga Reader",
    "app_icon" => "favicon.png",
    "app_css" => ["style.css"],
    "app_db_dir" => "db",
    "app_db_doc" => "eru123.mangareader-fs.app.db",
];

$app = new App($config);
$app->run("web");
$app->addChapter("solo","chapter 1");
$app->save();