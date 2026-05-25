<?php
$a = [-1, -2, 3, 5, 29, 50, 100];

$diffMethod1 = max($a) - min($a);

$sorted = $a;
sort($sorted, SORT_NUMERIC);
$diffMethod2 = $sorted[count($sorted) - 1] - $sorted[0];

$minValue = $a[0];
$maxValue = $a[0];
foreach ($a as $value) {
    if ($value < $minValue) {
        $minValue = $value;
    }
    if ($value > $maxValue) {
        $maxValue = $value;
    }
}
$diffMethod3 = $maxValue - $minValue;
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>练习1-最大最小差值</title>
    <style>
        body { font-family: "Microsoft YaHei", sans-serif; margin: 20px; }
        code { background: #f5f5f5; padding: 2px 6px; }
        li { margin: 8px 0; }
    </style>
</head>
<body>
<h1>无序数组最大值与最小值差</h1>
<p>数组：<code><?php echo htmlspecialchars('array(' . implode(', ', $a) . ')', ENT_QUOTES, 'UTF-8'); ?></code></p>
<ol>
    <li>方法一（max/min）：<?php echo $diffMethod1; ?></li>
    <li>方法二（排序后首尾相减）：<?php echo $diffMethod2; ?></li>
    <li>方法三（遍历比较）：<?php echo $diffMethod3; ?></li>
</ol>
</body>
</html>
