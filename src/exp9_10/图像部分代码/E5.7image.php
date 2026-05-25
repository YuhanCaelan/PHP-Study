<?php
declare(strict_types=1);

session_start();

$chars = '0123456789abcdefghijklmnopqrstuvwxyz';
$code = '';
for ($i = 0; $i < 5; $i++) {
    $code .= $chars[random_int(0, strlen($chars) - 1)];
}
$_SESSION['captcha_code'] = $code;

$width = 116;
$height = 38;
$lines = '';
for ($i = 0; $i < 8; $i++) {
    $x1 = random_int(0, $width);
    $y1 = random_int(0, $height);
    $x2 = random_int(0, $width);
    $y2 = random_int(0, $height);
    $color = sprintf('#%02x%02x%02x', random_int(120, 220), random_int(120, 220), random_int(120, 220));
    $lines .= "<line x1=\"{$x1}\" y1=\"{$y1}\" x2=\"{$x2}\" y2=\"{$y2}\" stroke=\"{$color}\" stroke-width=\"1\" />";
}

$dots = '';
for ($i = 0; $i < 80; $i++) {
    $cx = random_int(1, $width - 2);
    $cy = random_int(1, $height - 2);
    $color = sprintf('#%02x%02x%02x', random_int(80, 220), random_int(80, 220), random_int(80, 220));
    $dots .= "<circle cx=\"{$cx}\" cy=\"{$cy}\" r=\"1\" fill=\"{$color}\" />";
}

$letters = '';
for ($i = 0; $i < strlen($code); $i++) {
    $x = 14 + $i * 19;
    $y = random_int(24, 30);
    $rotate = random_int(-12, 12);
    $letter = htmlspecialchars($code[$i], ENT_QUOTES, 'UTF-8');
    $letters .= "<text x=\"{$x}\" y=\"{$y}\" transform=\"rotate({$rotate} {$x} {$y})\">{$letter}</text>";
}

header('Content-Type: image/svg+xml; charset=UTF-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
echo <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="{$width}" height="{$height}" viewBox="0 0 {$width} {$height}">
    <rect width="100%" height="100%" fill="#f8fafc"/>
    <rect x="0.5" y="0.5" width="115" height="37" fill="none" stroke="#96a2b3"/>
    {$lines}
    {$dots}
    <g fill="#204c84" font-size="22" font-family="Arial, sans-serif" font-weight="700">{$letters}</g>
</svg>
SVG;
