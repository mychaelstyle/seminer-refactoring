<?php

include(dirname(__FILE__) . '/../lib/Csv.php');

class MessageModel
{
    public static function read()
    {
        return Csv::read('chat.csv');
    }

    public static function save($params)
    {
        return Csv::write('chat.csv', array_merge($params, ['date' => date("Y.m.d H:i")]));
    }
}
