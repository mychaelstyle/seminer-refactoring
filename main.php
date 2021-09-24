<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>chat-App</title>
</head>

<body>
<?php
    $participants = [];
    $messages = [];
    $errors = [];

    init($participants, $messages);

    if (isset($_POST["chat"])) {
        if (validate($errors)) {
            sendMessage($_POST["name"], $_POST["message"]);
        }
    }

    /**
     * 初期化処理を行う
     *
     * @param array $participants
     * @param array $messages
     * @return void
     */
    function init(array &$participants, array &$messages): void {
        fetchParticipants($participants);
        fetchMessages($messages);
    }

    /**
     * チャット内容の一覧を取得する
     *
     * @param array $messages
     * @return void
     */
    function fetchMessages(array &$messages): void {
        if ($fp = fopen("chat.csv", "r")) {
            $row = 0;
            while (($data = fgetcsv($fp))) {
                // 1行目スキップ
                if ($row === 0) {
                    $row++;
                    continue;
                }

                $messages[] = [
                    'name' => $data[0],
                    'message' => $data[1],
                    'date' => $data[2]
                ];
                $row++;
            }
            fclose($fp);
        }
    }

    /**
     * 参加者の一覧を取得する
     *
     * @param array $participants
     * @return void
     */
    function fetchParticipants(array &$participants): void {
        if ($fp = fopen("chat.csv", "r")) {
            $row = 0;
            while (($data = fgetcsv($fp))) {
                // 1行目スキップ
                if ($row === 0) {
                    $row++;
                    continue;
                }

                $name = $data[0];
                if (!in_array($name, $participants, true)) {
                    $participants[] = $name;
                }
                $row++;
            }
            fclose($fp);
        }
    }

    /**
     * フォームパラメータのバリデーションを行う
     *
     * @param array $errors
     * @return bool
     */
    function validate(array &$errors): bool {
        if (empty($_POST['name'])) {
            $errors[] = '名前は必須項目です。';
        }

        if (empty($_POST['message'])) {
            $errors[] = '発言は必須項目です。';
        }

        return empty($errors);
    }

    /**
     * メッセージをチャットルームに投稿する
     *
     * @param string $name
     * @param string $message
     * @return void
     */
    function sendMessage(string $name, string $message) {
        if ($fp = fopen("chat.csv", "a")) {
            $line = implode(",", [$name, $message, date("Y/m/d H:i:s")]);
            fwrite($fp, "\n" . $line);
            fclose($fp);
        }
    }
?>

    <div class="layout" style="display:flex; border:solid 2px;">
        <aside class="sidebar" style="border-right:solid 2px; padding:5px;">
            <span>参加者</span>
            <ul style="list-style:none; padding:0;">
                <?php foreach ($participants as $participant): ?>
                    <li><?= $participant ?></li>
                <?php endforeach; ?>
            </ul>
        </aside>
        <div class="chat" style="padding:5px;">
            <!-- メッセージ表示 -->
            <div class="messages">
                <?php foreach ($messages as $msg): ?>
                    <p style="border-bottom:solid 1px;"><?= $msg["name"] ?>：<?= $msg["message"] ?> <?= $msg["date"] ?></p>
                <?php endforeach; ?>
            </div>
            <!-- メッセージ表示 END -->
            <form method="post" action="main.php">
                <input type="text" name="name" placeholder="名前">
                <input type="text" name="message" placeholder="発言を記入して送信">
                <button name="chat" type="submit">送信</button>
            </form>

            <!-- バリデーションエラー表示 -->
            <?php if (!empty($errors)): ?>
                <p style="color:red;">入力内容に誤りがあります。</p>
                <ul>
                    <?php foreach ($errors as $msg): ?>
                        <li style="color:red;"><?= $msg ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <!-- バリデーションエラー表示 END -->
        </div>
    </div>
</body>

</html>