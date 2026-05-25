<?php
$result = [];

for ($num = 100; $num <= 200; $num++) {
	$hundreds = intdiv($num, 100);
	$tens = intdiv($num % 100, 10);
	$ones = $num % 10;

	$sum = $hundreds ** 3 + $tens ** 3 + $ones ** 3;

	if ($sum === $num) {
		$result[] = $num;
	}
}

echo '100 到 200 之间的水仙花数有：';
echo empty($result) ? '无' : implode('，', $result);
