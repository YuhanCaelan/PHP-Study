<?php
include_once __DIR__ . '/compare_utils.php';

$inputA = $_POST['a'] ?? '';
$inputB = $_POST['b'] ?? '';
$inputC = $_POST['c'] ?? '';
$error = '';
$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!is_numeric($inputA) || !is_numeric($inputB) || !is_numeric($inputC)) {
        $error = 'Please enter valid numeric values.';
    } else {
        $result = compareThreeNumbers((float)$inputA, (float)$inputB, (float)$inputC);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Example 8 - Compare Three Numbers by Form</title>
</head>
<body>
    <h1>Compare Three Numbers (Form)</h1>

    <form method="post" action="example8.php">
        <label>A: <input type="text" name="a" value="<?php echo htmlspecialchars((string)$inputA); ?>"></label><br><br>
        <label>B: <input type="text" name="b" value="<?php echo htmlspecialchars((string)$inputB); ?>"></label><br><br>
        <label>C: <input type="text" name="c" value="<?php echo htmlspecialchars((string)$inputC); ?>"></label><br><br>
        <button type="submit">Compare</button>
    </form>

    <?php if ($error !== ''): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if (is_array($result)): ?>
        <p>Descending: <?php echo implode(' > ', $result); ?></p>
        <p>Largest: <?php echo $result[0]; ?></p>
        <p>Middle: <?php echo $result[1]; ?></p>
        <p>Smallest: <?php echo $result[2]; ?></p>
    <?php endif; ?>
</body>
</html>
