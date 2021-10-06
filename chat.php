<?php

    const LOG_FILE_PATH = 'chat.csv';

    date_default_timezone_set('Asia/Tokyo');

    $fp = fopen("chat.csv", "r");
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

        $members[] = $array[0];
    }

    // $uniqueMembers = array_unique($members);

    fclose($fp);

    if (isset($_POST['message']) && isset($_POST['name'])) {
        // $errors = validateRequest($_POST);

        storeMessage($_POST['message'], $_POST['name']);
    }

    function storeMessage(string $message, string $name)
    {
        $fp = fopen("./chat.csv", "a");
        $line = implode(",", [$name, $message, date("Y.m.d H:i")]);
        fwrite($fp, "\n".$line);
        fclose($fp);

        // ページ更新のためHTTPヘッダーを送信
        header("Location: " . $_SERVER['PHP_SELF'], true, 303);
    }

    function validateRequest($request): array
    {
        if (empty($request['message'])) {
            $errors[] = 'メッセージは必須です';
        }

        if (empty($request['name'])) {
            $errors[] = '名前は必須です';
        }

        return $errors;
    }

?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>chatアプリ</title>
	<link rel="stylesheet" href="style.css" type="text/css">
    <style type="text/css">
        @charset "utf-8";
        /*
        Version 1.0.0
        https://keninatateka.com/
        */

        /************ HTMLリセット ************************/

        body,div,h1,h2,h3,h4,h5,h6,p,ul,ol,li,dl,dt,dd,pre,blockquote,th,td,form,fieldset,input,textarea{margin:0;padding:0;}h1,h2,h3,h4,h5,h6{font-size:100%;font-weight:normal;}em,strong,code,address,th{font-weight:normal;font-style:normal;}ul,ol{list-style:none;}q:before,q:after{content:"";}abbr,acronym{border:0;}table{border-collapse:collapse;border-spacing:0;}th{text-align:left;}fieldset,img{border:0;}

        /************ HTML再定義 ************************/

        * {
            box-sizing: border-box;
            word-break: break-all;
        }
        body {
            min-width: 320px;
            background: #F2F3F6;
            line-height: 1.6;
            color: #1F2124;
            font-family: -apple-system, BlinkMacSystemFont, Consolas, "Hiragino Kaku Gothic Pro", "ヒラギノ角ゴ Pro W3", "Helvetica Neue", "ＭＳ Ｐゴシック", sans-serif;
            font-size: 16px;
            -webkit-text-size-adjust: 100%;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, Consolas, "Hiragino Kaku Gothic Pro", "ヒラギノ角ゴ Pro W3", "Helvetica Neue", "Yu Gothic", sans-serif;
            font-weight: 800;
        }
        h1 {
            height: 80px;
            margin: 0;
            padding: 0 30px;
            border-bottom: 1px solid #EEE;
            line-height: 80px;
            font-size: 160%;
            text-align: center;
        }
        h2 {
            font-size: 150%;
        }
        h3 {
            font-size: 140%;
        }
        h4 {
            font-size: 130%;
        }
        h5 {
            font-size: 120%;
        }
        h6 {
            font-size: 110%;
        }

        a {
            color: #000;
        }
        img {
            max-width: 100%;
            height: auto;
            vertical-align: middle;
        }

        /************ 全体 ************************/

        #container {
            overflow: hidden;
            width: 90%;
            max-width: 580px;
            height: 85vh;
            min-height: 220px;
            margin: 5vh auto;
            border-radius: 12px;
            background-color: #FFF;
            box-shadow: 0 8px 16px 3px rgba(0,0,0, 0.06);
        }
        .content {
            overflow-y: scroll;
            height: calc(85vh - 126px);

            -webkit-overflow-scrolling: touch;
        }

        @media only screen and (max-width: 420px) {
            h1 {
                height: 46px;
                padding: 0 15px;
                line-height: 46px;
                font-size: 120%;
            }
            #container {
                height: 80vh;
                margin: 2.5vh auto;
            }
            .content {
                height: calc(80vh - 92px);
            }
        }

        /************ メッセージ表示 ************************/

        .message-area {
            position: relative;
            margin: 0;
            padding: 5px 5%;
            font-size: 15px;
        }
        .message-area:first-child {
            padding-top: 30px;
        }
        .message-area:hover {
            background-color: #FAFAFA;
        }

        /* 相手 */
        .you {
            display: flex;
            align-items: top;
        }
        /* 自分 */
        .me {
            display: flex;
            justify-content: flex-end;
        }

        /* 画像 */
        .message-area img {
            display: block;
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        /* 日付 */
        .date {
            display: block;
            color: rgba(0,0,0, 0.4);
            font-size: 11px;
        }

        /* 相手のアイコン */
        .user-image {
            width: 38px;
            height: 38px;
            margin-right: 15px;
            border-radius: 100%;
            background-position: center;
            background-size: cover;
        }

        /* 相手のメッセージ */
        .message {
            margin: 0;
            padding: 5px 15px;
            border-radius: 8px;
            background-color: #B3DDFE;
            vertical-align: middle;
        }
        .message:before {
            position: absolute;
            left: 72px;
            margin-top: 5px;
            border-width: 0 10px 10px 0;
            border-style: solid;
            border-color: transparent #B3DDFE transparent transparent;
            content: "";
        }

        /* 自分のメッセージ */
        .mymessage {
            margin: 15px 0;
            padding: 5px 15px;
            border-radius: 8px;
            background-color: #EEE;
        }

        /************ フォーム ************************/

        form {
            margin: 0;
            padding: 0;
        }
        .form {
            position: relative;
            height: 46px;
            border-top: 1px solid #EEE;
        }
        .form input.text {
            width: 70%;
            height: 46px;
            padding-left: 5%;
            padding-right: 76px;
            border: none;
            font-family: inherit;
            font-size: 17px;
            float: left;
        }
        .form input.name {
            width: 30%;
            height: 46px;
            padding-left: 5%;
            padding-right: 76px;
            border: none;
            font-family: inherit;
            font-size: 17px;
            float: left;
        }
        .form button.submit {
            position: absolute;
            top: 0;
            right: 0;
            width: 66px;
            height: 46px;
            border: none;
            border-left: 1px solid #EEE;
            background-color: transparent;
            color: #1E87C9;
            font-family: inherit;
            font-size: 12px;
            cursor: pointer;
        }
        .form button:hover {
            color: #000;
        }
    </style>
</head>
<body>
    <div id="container">
        <h1>チャットアプリ</h1>
        <div class="content">
            <?php foreach ($messages as $msg): ?>
                <div class="message-area you">
                    <div class="user-image" style="background-image: url(img.png);"></div>
                    <div class="message"><?= $msg["name"] ?>：<?= $msg["message"] ?><span class="date"><?= $msg["date"] ?></span></div>
                </div>
            <?php endforeach; ?>

        </div>
        <div class="form">
            <form action="chat.php" method="post">
                <input type="text" class="name" name="name" placeholder="名前">
                <input type="text" class="text" name="message" placeholder="メッセージ">
                <button type="submit" class="submit">送信</button>
            </form>
        </div>
    </div>
    <?php if ($errors) : ?>
        <?php foreach ($errors as $error): ?>
            <p style="color: red"><?= $e ?></p>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>