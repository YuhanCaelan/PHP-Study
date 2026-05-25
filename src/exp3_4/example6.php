<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>使用while循环嵌套输出表格</title>
	<style>
		body {
			font-family: "Times New Roman", "Microsoft YaHei", serif;
			margin: 12px;
		}

		h1 {
			text-align: center;
			margin: 0 0 14px 0;
			font-size: 44px;
			font-weight: 700;
		}

		table {
			border-collapse: collapse;
			margin: 0 auto;
			border: 2px solid #bfbfbf;
		}

		td {
			width: 58px;
			height: 28px;
			border: 1px solid #bfbfbf;
			background: #e6e6e6;
			padding-left: 6px;
			vertical-align: middle;
			font-size: 28px;
		}
	</style>
</head>
<body>
	<h1>使用while循环嵌套输出表格</h1>

	<table>
		<?php
		$row = 0;
		while ($row <= 9) {
			echo '<tr>';

			$col = 0;
			while ($col <= 9) {
				$value = $row * 10 + $col;
				echo '<td>' . $value . '</td>';
				$col++;
			}

			echo '</tr>';
			$row++;
		}
		?>
	</table>
</body>
</html>
