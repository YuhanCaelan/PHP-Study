<?php
declare(strict_types=1);

session_start();

$message = '';
$messageClass = 'info';
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
        $_SESSION['username'] = $username;
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
    <title>用户登录验证码</title>
    <style>
        body { margin: 0; font-family: "Microsoft YaHei", Arial, sans-serif; background: #f1f3f5; color: #222; }
        .login-box { width: 360px; margin: 70px auto; padding: 24px 28px; background: #fff; border: 1px solid #d7dce1; border-radius: 8px; box-shadow: 0 8px 24px rgba(20, 32, 45, 0.08); }
        h1 { margin: 0 0 22px; font-size: 24px; text-align: center; }
        .row { display: grid; grid-template-columns: 80px 1fr; align-items: center; gap: 10px; margin-bottom: 14px; }
        label { text-align: right; }
        input[type="text"], input[type="password"] { height: 34px; padding: 0 10px; border: 1px solid #b8c1cc; border-radius: 4px; box-sizing: border-box; font-size: 15px; }
        .captcha-line { display: flex; gap: 8px; align-items: center; }
        .captcha-line input { width: 118px; }
        .captcha-line img { width: 116px; height: 38px; border: 1px solid #b8c1cc; border-radius: 4px; cursor: pointer; }
        .actions { margin-top: 18px; text-align: center; }
        button { min-width: 88px; height: 34px; margin: 0 6px; border: 1px solid #2878d4; border-radius: 4px; background: #2878d4; color: #fff; cursor: pointer; }
        button[type="reset"] { background: #fff; color: #2878d4; }
        .message { margin-bottom: 14px; padding: 9px 10px; border-radius: 4px; font-size: 14px; }
        .success { background: #e9f8ef; color: #146c2e; }
        .error { background: #fdecea; color: #b42318; }
    </style>
</head>
<body>
    <main class="login-box">
        <h1>用户登录</h1>
        <?php if ($message !== ''): ?>
            <div class="message <?= htmlspecialchars($messageClass, ENT_QUOTES, 'UTF-8') ?>">
                <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
            </div>
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
                    <img src="E5.7image.php" alt="验证码" title="单击刷新验证码" onclick="this.src='E5.7image.php?t=' + Math.random()">
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
