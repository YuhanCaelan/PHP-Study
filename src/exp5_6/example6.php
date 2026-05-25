<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>练习2-学号处理</title>
    <style>
        body { font-family: "Microsoft YaHei", sans-serif; margin: 18px; }
        .box { width: 60px; }
        .sep { margin: 0 4px; color: #777; }
        .result { margin-top: 16px; line-height: 1.6; }
    </style>
</head>
<body>
<?php
$inputIds = [];
$allIds = [];
$computerIds = [];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputIds = array_map('trim', $_POST['ids'] ?? []);

    if (count($inputIds) !== 5) {
        $error = '请完整输入5个学号。';
    } else {
        foreach ($inputIds as $id) {
            if (!preg_match('/^\d{6}$/', $id)) {
                $error = '学号应为6位数字。';
                break;
            }
        }
    }

    if ($error === '') {
        $allIds = array_values(array_unique($inputIds));

        foreach ($allIds as $id) {
            if (strpos($id, '0811') === 0) {
                $computerIds[] = '0810' . substr($id, 4);
            }
        }
    }
}
?>

<h2>请输入学号：</h2>
<form method="post">
    <?php for ($i = 0; $i < 5; $i++): ?>
        <input class="box" type="text" name="ids[]" value="<?php echo htmlspecialchars($inputIds[$i] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        <?php if ($i < 4): ?><span class="sep">-</span><?php endif; ?>
    <?php endfor; ?>
    <button type="submit">提交</button>
</form>

<div class="result">
    <?php if ($error !== ''): ?>
        <div style="color:#d00;"><?php echo $error; ?></div>
    <?php elseif (!empty($allIds)): ?>
        <div>所有的学生学号如下：</div>
        <div><?php echo htmlspecialchars(implode('，', $allIds), ENT_QUOTES, 'UTF-8'); ?></div>
        <div style="margin-top: 8px;">计算机专业的学号如下：</div>
        <div><?php echo htmlspecialchars(implode('，', $computerIds), ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>
</div>
</body>
</html>
