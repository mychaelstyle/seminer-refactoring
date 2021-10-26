<?php

include(dirname(__FILE__) . '/../lib/Csv.php');

class MessageModel
{
    public static function read()
    {
        return Csv::read('chat.csv');
    }

    public function save($message, $memberName)
    {
        // メッセージを保存する
    }
}
