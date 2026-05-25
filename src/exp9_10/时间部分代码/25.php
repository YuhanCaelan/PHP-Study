<?php
/**
 * DataTime类跟date(),strtotime(),gmdate()等函数有相同的作用，都是用来处理日期和时间的，
 * 但DateTime类更加直观、方便, 所以在PHP5.2.0以后推荐使用DateTime类而不是相应的函数。
 * /  
 */
//DateTime()使用
//1、获取当前系统时间并打印
$date = new DateTime();
echo $date->format('Y-m-d H:i:s');
echo "<br>";

//2、获取特定时间并打印
$date = new DateTime('2014-05-04');
echo $date->format('Y-m-d H:i:s');
echo "<br>";
$date2 = new DateTime('tomorrow');
echo $date2->format('Y-m-d H:i:s');
echo "<br>";
$date2 = new DateTime('+2 days');
echo $date2->format('Y-m-d H:i:s');
echo '<hr/>';
$date = new DateTime();
// add方法
$date->add(new DateInterval('P1D'));
echo $date->format('Y-m-d H:i:s');
echo "<br>";
// modify方法
$date->modify('+1 day');
echo $date->format('Y-m-d H:i:s');
echo "<br>";
// setDate方法
$date->setDate('1989','11','10');
echo $date->format('Y-m-d H:i:s');
echo "<br>";
// setTime方法
$date = new DateTime('2001-01-01');

$date->setTime(14, 55);
echo $date->format('Y-m-d H:i:s') . "<br>";

$date->setTime(14, 55, 24);
echo $date->format('Y-m-d H:i:s') . "<br>";

//unix时间戳的转换
//3. unix时间戳的转换
//获取当前时间的时间戳
$date = new DateTime();
echo $date->format('U');
echo "<br>";
//或者
$date = new DateTime();
echo $date->getTimestamp();
echo "<br>";

//将时间戳转换为可读时间

$date = new DateTime('@1408950651');

$date->setTimezone(new DateTimeZone('Asia/Shanghai'));
echo $date->format('Y-m-d H:i:s');
echo "<br>";

//或者
$date = new DateTime();
$date->setTimestamp(1408950651);
echo $date->format('Y-m-d H:i:s')."<br>";

//4. 日期的比较
//日期大小比较
$date1 = new DateTime();
$date2 = new DateTime('2022-12-15');

if($date1 < $date2) {
	echo $date2->format('Y-m-d H:i:s') . ' is in the future'."<br>";
}

///日期间隔
$date1 = new DateTime();
$date2 = new DateTime('2022-12-15');

$diff = $date1->diff($date2);
//print_r($diff);
//格式化输出
echo $diff->format("The future will come in %Y years %m months and %d days");