<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>chatアプリ</title>
	<link rel="stylesheet" href="css/style.css" type="text/css">
</head>
<body>
    <div id="container">
        <h1>チャットアプリ</h1>
        <div class="content">
            <?php foreach ($messages as $msg): ?>
                <div class="message-area you">
                    <div class="user-image" style="background-image: url(/image/img.png);"></div>
                    <div class="message"><?= $msg["name"] ?>：<?= $msg["message"] ?><span class="date"><?= $msg["date"] ?></span></div>
                </div>
            <?php endforeach; ?>

            <?php if (isset($errors)) : ?>
                <?php foreach ($errors as $error): ?>
                    <p style="color: red"><?= $error ?></p>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="form">
            <form action="/chat" method="post">
                <input type="text" class="name" name="name" placeholder="名前">
                <input type="text" class="text" name="message" placeholder="メッセージ">
                <button type="submit" class="submit">送信</button>
            </form>
        </div>
    </div>
    <?php foreach ($members as $member): ?>
            <p><?= $member ?></p>
    <?php endforeach; ?>
</body>
</html>