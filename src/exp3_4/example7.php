<?php
include_once __DIR__ . '/compare_utils.php';

$a = 24;
$b = 7;
$c = 35;

$sorted = compareThreeNumbers($a, $b, $c);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Example 7 - Compare Three Numbers</title>
</head>
<body>
    <h1>Compare Three Numbers</h1>
    <p>Numbers: <?php echo $a . ', ' . $b . ', ' . $c; ?></p>
    <p>Descending: <?php echo implode(' > ', $sorted); ?></p>
    <p>Largest: <?php echo $sorted[0]; ?></p>
    <p>Middle: <?php echo $sorted[1]; ?></p>
    <p>Smallest: <?php echo $sorted[2]; ?></p>
</body>
</html>
