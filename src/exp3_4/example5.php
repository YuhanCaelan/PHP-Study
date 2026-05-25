<?php
$numbers = [];

for ($num = 10; $num <= 99; $num++) {
	$tens = intdiv($num, 10);
	$ones = $num % 10;

	if ($ones > $tens) {
		$numbers[] = $num;
	}
}

$count = count($numbers);
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>两位整数筛选</title>
</head>
<body>
	<h3>个位数大于十位数的两位整数：</h3>
	<pre><?php
for ($i = 0; $i < $count; $i++) {
	echo str_pad((string)$numbers[$i], 4, ' ', STR_PAD_RIGHT);

	if (($i + 1) % 10 === 0) {
		echo PHP_EOL;
	}
}

if ($count % 10 !== 0) {
	echo PHP_EOL;
}
?></pre>

	<p>统计个数：<?php echo $count; ?></p>
</body>
</html>
