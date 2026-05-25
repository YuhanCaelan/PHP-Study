<?php
declare(strict_types=1);

date_default_timezone_set('Asia/Shanghai');

function calculateAge(string $birthday): ?int
{
    $date = DateTime::createFromFormat('Y-m-d', $birthday);
    $errors = DateTime::getLastErrors();
    if ($date === false || ($errors !== false && ($errors['warning_count'] > 0 || $errors['error_count'] > 0))) {
        return null;
    }

    $today = new DateTime('today');
    if ($date > $today) {
        return null;
    }

    return $date->diff($today)->y;
}

$birthday = trim((string) ($_POST['birthday'] ?? '2004-05-20'));
$age = calculateAge($birthday);
?>
<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>根据生日计算年龄</title>
    <style>
        body { margin: 40px; font-family: "Microsoft YaHei", Arial, sans-serif; background: #f5f6f8; color: #222; }
        .box { width: 420px; margin: 0 auto; padding: 24px; background: #fff; border: 1px solid #d7dce1; border-radius: 8px; }
        h1 { margin-top: 0; font-size: 24px; }
        input { width: 180px; height: 32px; padding: 0 8px; border: 1px solid #b8c1cc; border-radius: 4px; }
        button { height: 34px; margin-left: 8px; padding: 0 16px; border: 1px solid #2878d4; border-radius: 4px; background: #2878d4; color: #fff; cursor: pointer; }
        .result { margin-top: 18px; font-size: 18px; }
        .error { margin-top: 18px; color: #b42318; }
    </style>
</head>
<body>
    <main class="box">
        <h1>根据生日计算年龄</h1>
        <form method="post">
            <label for="birthday">生日：</label>
            <input id="birthday" name="birthday" type="date" value="<?= htmlspecialchars($birthday, ENT_QUOTES, 'UTF-8') ?>">
            <button type="submit">计算</button>
        </form>

        <?php if ($age === null): ?>
            <div class="error">请输入不晚于今天的有效生日。</div>
        <?php else: ?>
            <div class="result"><?= htmlspecialchars($birthday, ENT_QUOTES, 'UTF-8') ?> 出生的人今年 <?= $age ?> 周岁。</div>
        <?php endif; ?>
    </main>
</body>
</html>
