<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>练习1-排序</title>
    <style>
        body { font-family: "Microsoft YaHei", sans-serif; margin: 18px; }
        .box { width: 46px; }
        .sep { margin: 0 4px; color: #777; }
        .result { margin-top: 16px; line-height: 1.8; }
    </style>
</head>
<body>
<?php
$inputNumbers = [];
$sortedNumbers = [];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputNumbers = $_POST['nums'] ?? [];
    $inputNumbers = array_map('trim', $inputNumbers);

    if (count($inputNumbers) !== 5) {
        $error = '请完整输入5个数。';
    } else {
        foreach ($inputNumbers as $num) {
            if ($num === '' || !is_numeric($num)) {
                $error = '输入有误，请输入有效数字。';
                break;
            }
        }
    }

    if ($error === '') {
        $sortedNumbers = $inputNumbers;
        sort($sortedNumbers, SORT_NUMERIC);
    }
}
?>

<h2>请输入需要排序的数据：</h2>
<form method="post">
    <?php for ($i = 0; $i < 5; $i++): ?>
        <input class="box" type="text" name="nums[]" value="<?php echo htmlspecialchars($inputNumbers[$i] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        <?php if ($i < 4): ?><span class="sep">-</span><?php endif; ?>
    <?php endfor; ?>
    <button type="submit">提交</button>
</form>

<div class="result">
    <?php if ($error !== ''): ?>
        <div style="color: #d00;"><?php echo $error; ?></div>
    <?php elseif (!empty($inputNumbers)): ?>
        <div>您输入的数据有：</div>
        <div><?php echo implode('<br>', array_map('htmlspecialchars', $inputNumbers)); ?></div>
        <div style="margin-top: 8px;">排序后的数据如下所示：</div>
        <div><?php echo implode('<br>', array_map('htmlspecialchars', $sortedNumbers)); ?></div>
    <?php endif; ?>
</div>
</body>
</html>
