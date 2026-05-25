<?php
$redPool = range(1, 33);
shuffle($redPool);
$redBalls = array_slice($redPool, 0, 6);
sort($redBalls, SORT_NUMERIC);
$blueBall = random_int(1, 16);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>练习1-双色球</title>
    <style>
        body { margin: 0; padding: 10px; background: #efefef; font-family: "Microsoft YaHei", sans-serif; }
        .panel { display: inline-flex; padding: 12px 8px; background: #fff; border-radius: 2px; }
        .ball {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            color: #fff;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 6px;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.4);
        }
        .red {
            background: radial-gradient(circle at 12px 12px, #ff2b2b, #7a0000);
        }
        .blue {
            background: radial-gradient(circle at 12px 12px, #2257ff, #000d75);
        }
        .actions { margin-top: 10px; }
    </style>
</head>
<body>
    <div class="panel">
        <?php foreach ($redBalls as $num): ?>
            <div class="ball red"><?php echo str_pad((string)$num, 2, '0', STR_PAD_LEFT); ?></div>
        <?php endforeach; ?>
        <div class="ball blue"><?php echo str_pad((string)$blueBall, 2, '0', STR_PAD_LEFT); ?></div>
    </div>
    <div class="actions">
        <a href="<?php echo htmlspecialchars(basename($_SERVER['PHP_SELF']), ENT_QUOTES, 'UTF-8'); ?>">换一注</a>
    </div>
</body>
</html>
