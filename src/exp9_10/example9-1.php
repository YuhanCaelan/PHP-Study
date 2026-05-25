<?php
declare(strict_types=1);

session_start();

function randomCaptchaCode(int $length = 5): string
{
    $chars = '0123456789abcdefghijklmnopqrstuvwxyz';
    $code = '';

    for ($i = 0; $i < $length; $i++) {
        $code .= $chars[random_int(0, strlen($chars) - 1)];
    }

    return $code;
}

function outputCaptchaImage(): void
{
    $code = randomCaptchaCode();
    $_SESSION['captcha_code'] = $code;

    $width = 116;
    $height = 38;
    $lines = '';
    $dots = '';
    $letters = '';

    for ($i = 0; $i < 8; $i++) {
        $x1 = random_int(0, $width);
        $y1 = random_int(0, $height);
        $x2 = random_int(0, $width);
        $y2 = random_int(0, $height);
        $color = sprintf('#%02x%02x%02x', random_int(120, 220), random_int(120, 220), random_int(120, 220));
        $lines .= "<line x1=\"{$x1}\" y1=\"{$y1}\" x2=\"{$x2}\" y2=\"{$y2}\" stroke=\"{$color}\" stroke-width=\"1\" />";
    }

    for ($i = 0; $i < 80; $i++) {
        $cx = random_int(1, $width - 2);
        $cy = random_int(1, $height - 2);
        $color = sprintf('#%02x%02x%02x', random_int(80, 220), random_int(80, 220), random_int(80, 220));
        $dots .= "<circle cx=\"{$cx}\" cy=\"{$cy}\" r=\"1\" fill=\"{$color}\" />";
    }

    for ($i = 0; $i < strlen($code); $i++) {
        $x = 14 + $i * 19;
        $y = random_int(24, 30);
        $rotate = random_int(-12, 12);
        $letter = htmlspecialchars($code[$i], ENT_QUOTES, 'UTF-8');
        $letters .= "<text x=\"{$x}\" y=\"{$y}\" transform=\"rotate({$rotate} {$x} {$y})\">{$letter}</text>";
    }

    header('Content-Type: image/svg+xml; charset=UTF-8');
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    echo <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="{$width}" height="{$height}" viewBox="0 0 {$width} {$height}">
    <rect width="100%" height="100%" fill="#f8fafc"/>
    <rect x="0.5" y="0.5" width="115" height="37" fill="none" stroke="#96a2b3"/>
    {$lines}
    {$dots}
    <g fill="#204c84" font-size="22" font-family="Arial, sans-serif" font-weight="700">{$letters}</g>
</svg>
SVG;
}

if (isset($_GET['captcha'])) {
    outputCaptchaImage();
    exit;
}

$message = '';
$messageClass = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $captcha = strtolower(trim((string) ($_POST['captcha'] ?? '')));
    $savedCaptcha = strtolower((string) ($_SESSION['captcha_code'] ?? ''));

    if ($username !== 'a' || $password !== '123456') {
        $message = '登录失败：用户名或密码错误。';
        $messageClass = 'error';
    } elseif ($savedCaptcha === '' || !hash_equals($savedCaptcha, $captcha)) {
        $message = '登录失败：验证码输入错误。';
        $messageClass = 'error';
    } else {
        $message = '登录成功！';
        $messageClass = 'success';
    }
}
?>
<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>随机验证码登录</title>
    <style>
        body { margin: 0; font-family: "Microsoft YaHei", Arial, sans-serif; background: #f1f3f5; color: #222; }
        .box { width: 360px; margin: 70px auto; padding: 24px 28px; background: #fff; border: 1px solid #d7dce1; border-radius: 8px; }
        h1 { margin: 0 0 22px; font-size: 24px; text-align: center; }
        .row { display: grid; grid-template-columns: 80px 1fr; align-items: center; gap: 10px; margin-bottom: 14px; }
        label { text-align: right; }
        input[type="text"], input[type="password"] { height: 34px; padding: 0 10px; border: 1px solid #b8c1cc; border-radius: 4px; box-sizing: border-box; }
        .captcha-line { display: flex; gap: 8px; align-items: center; }
        .captcha-line input { width: 118px; }
        .captcha-line img { width: 116px; height: 38px; border: 1px solid #b8c1cc; border-radius: 4px; cursor: pointer; }
        .actions { text-align: center; }
        button { min-width: 88px; height: 34px; margin: 0 6px; border: 1px solid #2878d4; border-radius: 4px; background: #2878d4; color: #fff; cursor: pointer; }
        button[type="reset"] { background: #fff; color: #2878d4; }
        .message { margin-bottom: 14px; padding: 9px 10px; border-radius: 4px; }
        .success { background: #e9f8ef; color: #146c2e; }
        .error { background: #fdecea; color: #b42318; }
    </style>
</head>
<body>
    <main class="box">
        <h1>用户登录</h1>
        <?php if ($message !== ''): ?>
            <div class="message <?= htmlspecialchars($messageClass, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="row">
                <label for="username">用户名</label>
                <input id="username" name="username" type="text" value="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="row">
                <label for="password">密码</label>
                <input id="password" name="password" type="password">
            </div>
            <div class="row">
                <label for="captcha">验证码</label>
                <div class="captcha-line">
                    <input id="captcha" name="captcha" type="text" autocomplete="off">
                    <img src="example9-1.php?captcha=1" alt="验证码" onclick="this.src='example9-1.php?captcha=1&t=' + Math.random()">
                </div>
            </div>
            <div class="actions">
                <button type="submit">登录</button>
                <button type="reset">重置</button>
            </div>
        </form>
    </main>
</body>
</html>
