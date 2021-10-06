<?php

$name = $_POST['user'];
$message = $_POST['message'];

var_dump([$name, $message]);


const DB_FILE_PATH = '../storage/db.csv';
$db = file_get_contents(DB_FILE_PATH);
file_put_contents(DB_FILE_PATH, $db . "{$name},{$message}\n");

header('Location: /');
