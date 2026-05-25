<?php
declare(strict_types=1);

date_default_timezone_set('Asia/Shanghai');

$name = trim((string) ($_POST['name'] ?? '毕业纪念日'));
$targetInput = trim((string) ($_POST['target'] ?? '2026-06-30 00:00:00'));
$target = DateTime::createFromFormat('Y-m-d\TH:i', $targetInput);
if ($target === false) {
    $target = DateTime::createFromFormat('Y-m-d H:i:s', $targetInput);
}

$now = new DateTime();
$valid = $target instanceof DateTime;
$seconds = $valid ? $target->getTimestamp() - $now->getTimestamp() : 0;
$past = $seconds < 0;
$seconds = abs($seconds);
$days = intdiv($seconds, 86400);
$hours = intdiv($seconds % 86400, 3600);
$minutes = intdiv($seconds % 3600, 60);
$remainSeconds = $seconds % 60;
$targetValue = $valid ? $target->format('Y-m-d\TH:i') : '2026-06-30T00:00';
?>
<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>纪念日倒计时</title>
    <style>
        body { margin: 40px; font-family: "Microsoft YaHei", Arial, sans-serif; background: #f5f6f8; color: #222; }
        .box { width: 520px; margin: 0 auto; padding: 24px; background: #fff; border: 1px solid #d7dce1; border-radius: 8px; }
        h1 { margin-top: 0; font-size: 24px; }
        .row { margin-bottom: 14px; }
        label { display: inline-block; width: 90px; }
        input { height: 32px; padding: 0 8px; border: 1px solid #b8c1cc; border-radius: 4px; }
        button { height: 34px; padding: 0 16px; border: 1px solid #2878d4; border-radius: 4px; background: #2878d4; color: #fff; cursor: pointer; }
        .countdown { margin-top: 20px; padding: 18px; background: #eef6ff; border-radius: 6px; font-size: 20px; line-height: 1.8; }
        strong { font-size: 28px; color: #175cd3; }
        .error { color: #b42318; }
    </style>
</head>
<body>
    <main class="box">
        <h1>纪念日倒计时</h1>
        <form method="post">
            <div class="row">
                <label for="name">纪念日</label>
                <input id="name" name="name" type="text" value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="row">
                <label for="target">目标时间</label>
                <input id="target" name="target" type="datetime-local" value="<?= htmlspecialchars($targetValue, ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <button type="submit">开始计算</button>
        </form>
        <div class="countdown">
            <?php if (!$valid): ?>
                <span class="error">请输入有效的纪念日时间。</span>
            <?php elseif ($past): ?>
                <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?> 已经过了 <strong><?= $days ?></strong> 天 <?= $hours ?> 小时 <?= $minutes ?> 分 <?= $remainSeconds ?> 秒。
            <?php else: ?>
                距离 <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?> 还有 <strong><?= $days ?></strong> 天 <?= $hours ?> 小时 <?= $minutes ?> 分 <?= $remainSeconds ?> 秒。
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
