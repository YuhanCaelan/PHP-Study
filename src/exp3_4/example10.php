<?php
function fibonacciRecursive($n)
{
    if ($n <= 2) {
        return 1;
    }
    return fibonacciRecursive($n - 1) + fibonacciRecursive($n - 2);
}
function fibonacciIterative($count)
{
    if ($count <= 0) {
        return [];
    }
    if ($count === 1) {
        return [1];
    }
    $sequence = [1, 1];

    for ($i = 3; $i <= $count; $i++) {
        $sequence[] = $sequence[count($sequence) - 1] + $sequence[count($sequence) - 2];
    }
    return $sequence;
}
$count = 30;
$iterative = fibonacciIterative($count);
$recursive = [];
for ($i = 1; $i <= $count; $i++) {
    $recursive[] = fibonacciRecursive($i);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Example 10 - Fibonacci</title>
</head>
<body>
    <h1>Fibonacci First 30 Terms</h1>

    <h2>Iterative</h2>
    <p><?php echo implode(', ', $iterative); ?></p>

    <h2>Recursive</h2>
    <p><?php echo implode(', ', $recursive); ?></p>
</body>
</html>
