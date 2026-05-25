<?php define('APP', true); ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>练习1-网页布局</title>
    <link rel="stylesheet" href="/exp5_6/layout/CSS/style.css">
</head>
<body>
    <div class="title">header</div>
    <div class="main">
        <div class="content"><?php include __DIR__ . '/content.php'; ?></div>
        <div class="side"><?php include __DIR__ . '/side.php'; ?></div>
    </div>
    <div class="footer">footer</div>
</body>
</html>
