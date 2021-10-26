<?php

class Csv
{
    public static function read($fileName)
    {
        $fp = fopen(dirname(__FILE__) . "/../../storage/csv/" . $fileName, "r");

        $messages = [];
        while (($array = fgetcsv($fp)) !== false) {
            //空行を取り除く
            if (!array_diff($array, array(''))) {
                continue;
            }
        
            $messages[] = [
                'name' => $array[0],
                'message' => $array[1],
                'date' => $array[2]
            ];
        }
        return $messages;
    }
}
