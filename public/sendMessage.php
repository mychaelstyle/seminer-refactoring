<?php

session_start();

$result = validate();

if (!$result) {
    header('Location: /');
    saveErrorMessage('ユーザー名とメッセージは必ず設定してください');
    exit();
}

$name = $_POST['user'];
$message = $_POST['message'];

const DB_FILE_PATH = '../storage/db.csv';

$db = file_get_contents(DB_FILE_PATH);
file_put_contents(DB_FILE_PATH, $db . "{$name},{$message}\n");

header('Location: /');

/**
 * バリデーション
 *
 * @return boolean if true 正常
 */
function validate() {
    if (!isset($_POST['user']) || !isset($_POST['message'])) {
        header('Location: /');
        saveErrorMessage('ユーザー名とメッセージは必ず設定してください');
        return false;
    }

    if (empty($_POST['user']) || empty($_POST['message'])) {
        header('Location: /');
        saveErrorMessage('ユーザー名とメッセージは必ず設定してください');
        return false;
    }

    return true;
}

/**
 * Undocumented function
 *
 * @param string $message エラーメッセージ
 * @return void
 */
function saveErrorMessage(string $message) {
    $_SESSION['error'] = $message;
}
