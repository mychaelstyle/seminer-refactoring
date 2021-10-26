<?php
if (empty($_SERVER['REQUEST_URI'])) {
    exit;
}

date_default_timezone_set('Asia/Tokyo');

// URLをスラッシュで分解
$arrayParseUri = explode('/', $_SERVER['REQUEST_URI']);
$lastUri = end($arrayParseUri); // 最後の文字を取り出す
$call = substr($lastUri, 0, strcspn($lastUri, '?'));   // クエリ文字列を外す
$callHead = substr($call, 0, 1);
$clazz = strtoupper($callHead) . substr($call, 1) . 'Controller';

// app/controller/配下に同名のPHPファイルがないか探す。
if (file_exists('../app/controller/' . $clazz . '.php')) {
    // 見つかったファイルをインクルードしてコントローラーをインスタンス化
    include('../app/controller/' . $clazz . '.php');
    $obj   = new $clazz();
    
    if ($_SERVER["REQUEST_METHOD"] === "GET") {
        $response = $obj->index();
    } else {
        $response = $obj->store($_POST);
    }

    // コントローラーから戻された内容をレスポンスとして戻す。
    echo $response;
    exit;
} else {
    // ファイルがなければ404エラー
    header("HTTP/1.0 404 Not Found");
    exit;
}
