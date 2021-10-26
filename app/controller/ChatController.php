<?php

include(dirname(__FILE__) . '/../lib/View.php');
include(dirname(__FILE__) . '/../model/MessageModel.php');

class ChatController
{
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
