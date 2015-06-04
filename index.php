<?php
header("Content-Type: text/html; charset=UTF-8");
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT |E_NOTICE |E_WARNING);
define('ROOT', __DIR__);
include ROOT . '/common/Mongo.class.php';

