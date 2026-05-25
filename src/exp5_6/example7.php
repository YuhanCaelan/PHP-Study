<?php
function getFileExtension($filename)
{
    $path = parse_url($filename, PHP_URL_PATH);
    $extension = pathinfo((string)$path, PATHINFO_EXTENSION);
    return $extension !== '' ? strtolower($extension) : '';
}

$input = '';
$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = trim($_POST['filename'] ?? '');
    $result = getFileExtension($input);
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>练习2-获取后缀名</title>
    <style>
        body { font-family: "Microsoft YaHei", sans-serif; margin: 20px; }
        input[type=text] { width: 360px; }
        .examples { margin-top: 14px; color: #555; }
    </style>
</head>
<body>
<h1>获取文件后缀名</h1>
<form method="post">
    <label for="filename">请输入文件名/路径/URL：</label>
    <input id="filename" type="text" name="filename" value="<?php echo htmlspecialchars($input, ENT_QUOTES, 'UTF-8'); ?>" placeholder="例如：/var/www/index.php">
    <button type="submit">获取后缀</button>
</form>

<?php if ($result !== null): ?>
    <p>后缀名：<?php echo $result === '' ? '无后缀名' : htmlspecialchars($result, ENT_QUOTES, 'UTF-8'); ?></p>
<?php endif; ?>

<div class="examples">
    示例：
    <?php
    $samples = ['a.txt', 'archive.tar.gz', 'https://a.com/path/image.JPG?x=1', 'README'];
    foreach ($samples as $sample) {
        echo '<div>' . htmlspecialchars($sample, ENT_QUOTES, 'UTF-8') . ' -> ' . htmlspecialchars(getFileExtension($sample), ENT_QUOTES, 'UTF-8') . '</div>';
    }
    ?>
</div>
</body>
</html>
