<?php
  const DB_FILE_PATH = '../storage/db.csv';
  $db = file_get_contents(DB_FILE_PATH);
  $lines = explode("\n", $db);
  $messages = array_map(function ($line) {
    if (strlen($line) === 0) {
      return null;
    }
    list($name, $message) = explode(',', $line);
    return ["name" => $name, "message" => $message];
  }, $lines);
?>
<h1>チャット</h1>
<ul>
  <?php foreach ($messages as $message) { ?>
    <?php if ($message !== null) { ?>
      <li><?php echo $message['name'];?>: <?php echo $message['message']; ?></li>
    <?php } ?>
  <?php } ?>
</ul>
<form method="post" action="/sendMessage.php">
  お名前
  <input name="user" type="text" value="" placeholder="あなたのお名前">
  発言
  <input name="message" type="text" value="" placeholder="メッセージ" />
  <button type="submit">送信</button>
</form>
