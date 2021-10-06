<?php

session_start();

/** DBファイルの場所 */
const DB_FILE_PATH = '../storage/db.csv';

$error = fetchErrorMessage();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $result = validate();

    if ($result) {
        $name = $_POST['user'];
        $message = $_POST['message'];
    
        $db = file_get_contents(DB_FILE_PATH);
        file_put_contents(DB_FILE_PATH, $db . "{$name},{$message}\n");
    } else {
        saveErrorMessage('ユーザー名とメッセージは必ず設定してください');
    }

    header('Location: /');
    exit();
}

?>
<html>
  <head>
    <title>チャット！！</title>
  </head>
  <body>
    <h1>チャット</h1>
    <?php if ($error !== null) { ?>
        <div style="color: red;"><?php echo $error; ?></div>
    <?php } ?>
    <ul>
      <?php foreach (fetchMessages() as $message) { ?>
        <?php if ($message !== null) { ?>
          <li><?php echo $message['name'];?>: <?php echo $message['message']; ?></li>
        <?php } ?>
      <?php } ?>
    </ul>
    <form method="post" action="/">
      お名前
      <input name="user" type="text" value="" placeholder="あなたのお名前">
      発言
      <input name="message" type="text" value="" placeholder="メッセージ" />
      <button type="submit">送信</button>
    </form>
  </body>
</html>
<?php

/**
 * バリデーション
 *
 * @return boolean if true 正常
 */
function validate()
{
    if (!isset($_POST['user']) || !isset($_POST['message'])) {
        return false;
    }

    if (empty($_POST['user']) || empty($_POST['message'])) {
        return false;
    }

    return true;
}

/**
 * メッセージを読み込んで配列で返す
 *
 * @return array
 */
function fetchMessages()
{
    $db = file_get_contents(DB_FILE_PATH);
    $lines = explode("\n", $db);
    $callback = function ($line) {
        if (strlen($line) === 0) {
            return null;
        }
        list($name, $message) = explode(',', $line);
        return ["name" => $name, "message" => $message];
    };
    return array_map($callback, $lines);
}

/**
 * Undocumented function
 *
 * @param string $message エラーメッセージ
 */
function saveErrorMessage(string $message) {
    $_SESSION['error'] = $message;
}

/**
 * セッションに保存されたエラーメッセージを取得
 *
 * @return string | null
 */
function fetchErrorMessage() {
    if (!isset($_SESSION['error'])) {
        return null;
    }
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
    return $error;
}
?>
