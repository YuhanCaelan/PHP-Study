<?php
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$username = $_POST['username'] ?? '';
	$password = $_POST['password'] ?? '';

	if ($username === 'user' && $password === '123456') {
		$message = '登录成功';
	} else {
		$message = '登录失败：登录名或密码错误';
	}
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="UTF-8">
	<title>用户登录</title>
	<style>
		body {
			font-family: "Microsoft YaHei", sans-serif;
			background: #f5f7fa;
		}
		.login-box {
			width: 360px;
			margin: 60px auto;
			padding: 20px;
			border: 1px solid #d9d9d9;
			background: #fff;
		}
		h2 {
			margin-top: 0;
			text-align: center;
		}
		.row {
			margin: 12px 0;
		}
		label {
			display: inline-block;
			width: 70px;
		}
		input[type="text"],
		input[type="password"] {
			width: 240px;
			padding: 6px;
			box-sizing: border-box;
		}
		.btn {
			margin-left: 70px;
			padding: 6px 18px;
		}
		.msg {
			margin-top: 14px;
			margin-left: 70px;
			color: #1f5fbf;
		}
	</style>
</head>
<body>
	<div class="login-box">
		<h2>登录表单</h2>
		<form method="post" action="">
			<div class="row">
				<label for="username">登录名</label>
				<input type="text" id="username" name="username" required>
			</div>
			<div class="row">
				<label for="password">密码</label>
				<input type="password" id="password" name="password" required>
			</div>
			<div class="row">
				<input class="btn" type="submit" value="提交">
			</div>
		</form>

		<?php if ($message !== ''): ?>
			<div class="msg"><?php echo htmlspecialchars($message); ?></div>
		<?php endif; ?>
	</div>
</body>
</html>
