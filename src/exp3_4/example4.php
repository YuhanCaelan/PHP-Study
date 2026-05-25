<?php
function isLeapYear(int $year): bool
{
	return ($year % 400 === 0) || ($year % 4 === 0 && $year % 100 !== 0);
}

$inputYear = '';
$judgeText = '请先输入年份并提交。';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$inputYear = trim($_POST['year'] ?? '');

	if (preg_match('/^-?\d+$/', $inputYear)) {
		$year = (int)$inputYear;
		$judgeText = $year . (isLeapYear($year) ? '年是闰年' : '年不是闰年');
	} else {
		$judgeText = '输入错误：请输入整数年份。';
	}
}

$leapYears = [];
for ($year = 2000; $year <= 2030; $year++) {
	if (isLeapYear($year)) {
		$leapYears[] = $year;
	}
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>闰年的判断</title>
	<style>
		body {
			margin: 0;
			padding: 24px;
			background: #f5f7fa;
			font-family: "Microsoft YaHei", sans-serif;
		}

		.panel {
			max-width: 680px;
			background: #dfe6ec;
			border: 4px solid #b9d8ef;
			padding: 18px 16px;
		}

		h2 {
			margin: 0 0 18px 0;
			text-align: center;
			font-weight: 700;
		}

		.row {
			margin: 10px 0;
			font-size: 20px;
			line-height: 1.8;
		}

		input[type="text"] {
			width: 170px;
			height: 30px;
			font-size: 18px;
			padding: 2px 6px;
		}

		button {
			height: 36px;
			padding: 0 14px;
			font-size: 16px;
			margin-left: 8px;
			cursor: pointer;
		}
	</style>
</head>
<body>
	<div class="panel">
		<h2>闰年的判断</h2>
		<form method="post" action="example4.php">
			<div class="row">
				<label for="year">输入的年份：</label>
				<input id="year" type="text" name="year" value="<?php echo htmlspecialchars($inputYear); ?>" placeholder="如 2015">
				<button type="submit">判断</button>
			</div>
		</form>

		<div class="row">判断的结果：<?php echo htmlspecialchars($judgeText); ?></div>
		<div class="row">2000-2030 年的闰年：<?php echo implode('、', $leapYears); ?></div>
	</div>
</body>
</html>
