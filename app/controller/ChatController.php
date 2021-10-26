<?php

include(dirname(__FILE__) . '/../lib/View.php');
include(dirname(__FILE__) . '/../model/MessageModel.php');
class ChatController
{
    // const LOG_FILE_PATH = 'chat.csv';

    public function index()
    {
        $messages = MessageModel::read();
        $members = $this->memberExtraction($messages);
        
        View::render('chat', ['messages' => $messages, 'members' => $members]);
    }

    public function store($params)
    {
        $errors = $this->validateRequest($params);

        if (!empty($errors)) {
            $messages = MessageModel::read();
            $members = $this->memberExtraction($messages);
            View::render('chat', ['errors' => $errors, 'messages' => $messages, 'members' => $members]);
        } else {
            MessageModel::save($params);
            header("Location: " . $_SERVER['PHP_SELF'], true, 302);
        }
    }

    private function memberExtraction($messages)
    {
        $members = array_unique(array_map(function ($message) {
            return $message['name'];
        }, $messages));

        return $members;
    }

    // public function index()
    // {
    //     $fp = fopen("/csv/chat.csv", "r");
    //     while (($array = fgetcsv($fp)) !== false) {
    //         //空行を取り除く
    //         if (!array_diff($array, array(''))) {
    //             continue;
    //         }
        
    //         $messages[] = [
    //         'name' => $array[0],
    //         'message' => $array[1],
    //         'date' => $array[2]
    //     ];
    
    //         $members[] = $array[0];
    //     }
    
    
    //     // $uniqueMembers = array_unique($members);
    
    //     fclose($fp);
    // }

    // public function storeMessage(string $message, string $name)
    // {
    //     $fp = fopen("./chat.csv", "a");
    //     $line = implode(",", [$name, $message, date("Y.m.d H:i")]);
    //     fwrite($fp, "\n".$line);
    //     fclose($fp);

    //     // ページ更新のためHTTPヘッダーを送信
    //     header("Location: " . $_SERVER['PHP_SELF'], true, 303);
    // }

    /**
     * バリデーション
     *
     * @return boolean if true 正常
     */
    public function validateRequest($request): array
    {
        $errors = [];

        if (empty($request['message'])) {
            $errors[] = 'メッセージは必須です';
        }

        if (empty($request['name'])) {
            $errors[] = '名前は必須です';
        }

        return $errors;
    }
}
