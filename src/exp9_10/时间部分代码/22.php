<?php
//日期和时间:
//php.ini:date.timezone=Asia/Shanghai或PRC
//1970年以前日期的时间戳是负数
echo "2023-05-20 10:24:30时间戳:".strtotime("2023-05-20 10:24:30")."<br>";
echo "当前时间的时间戳:".strtotime("now")."<br>";
echo "明天此刻的时间戳:".strtotime("+1day")."<br>";
echo "1916-05-12 10:24:30时间戳:".strtotime("1916-05-12 10:24:30")."<br>";
echo "格式化输出时间：".date("Y-m-d H:i:s",2556115199)."<br>";//2050-12-31 23:59:59本机不受漏洞影响
echo "格式化输出时间：".date("Y-m-d H:i:s",-1692740130)."<br>";
//echo mktime()."<br>";
echo "2024-5-12 0时0分0秒的时间戳：".mktime(0,0,0,5,12,2024)."<br>";
echo "当前时间的时间戳:".time()."<br>";
echo "格式化输出当前时间：".date("Y-m-d H:i:s",time())."<br>";
echo "格式化输出当前时间：".date("jS-F-Y")."<br>";
echo "获得当前日期和时间信息:<br>";
print_r(getdate(time()));
echo "<br>";

header('Content-Type: text/html; charset=GBK');
