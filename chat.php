<?php

class Chat
{

  public function history()
  {
    $csv = file('./chat.csv');
    var_dump($csv[0]);
    // $chatHistory = explode(',', $csv);
    // var_dump($csv[0]);
    return $csv;
  }

  public function post($request)
  {
    $name = $request['name'];
    $contents = $request['contents'];
    $time = date('H:i:s');

    $history = $this->history();
    array_push($history, [$name, $contents, $time]);

    $file_handler = fopen('./chat.csv', "w");
    fputcsv($file_handler, $history);
  }

  public function get()
  {
    $history = $this->history();
    $res = [];
    foreach ($history as $row) {
      $name = $row[0];
      $contents = $row[1];
      $time = $row[2];

      $res[] = $name . '： ' . $contents . '　' . $time;
    }

    return $res;
  }
}

$Chat = new Chat;
$Chat->post($_POST);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>chat</title>
</head>

<body>
  <div style='display:flex'>
    <aside style='width:25%'>
      aaaaaa
      aaaaaa
    </aside>
    <main>
      <ul>
        <?php
        $chatContents = $Chat->get();
        foreach ($chatContents as $value) {
        ?>
          <li><?= $value ?></li>
        <?php
        }
        ?>
      </ul>
      <form method='POST' action='chat.php' style='display:flex'>
        <input name='name' placeholder="名前" />
        <input name='contents' placeholder="発言を記入して送信" />
        <button type='submit'>送信</button>
      </form>
    </main>
  </div>
</body>

</html>