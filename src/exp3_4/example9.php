<?php

function primesWithin100()
{
    $primes = [];

    for ($num = 2; $num <= 100; $num++) {
        $isPrime = true;
        for ($i = 2; $i * $i <= $num; $i++) {
            if ($num % $i === 0) {
                $isPrime = false;
                break;
            }
        }

        if ($isPrime) {
            $primes[] = $num;
        }
    }

    return $primes;
}

$result = primesWithin100();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Example 9 - Prime Numbers</title>
</head>
<body>
    <h1>Prime Numbers Within 100</h1>
    <p><?php echo implode(', ', $result); ?></p>
</body>
</html>
