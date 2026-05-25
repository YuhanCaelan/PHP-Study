<?php
if (!defined('APP')) {
    exit('error!');
}

$classes = [
    'PHP基础班' => [
        '北京-第35期（2021年03月06号）',
        '北京-第36期（2021年04月06号）',
        '广州-第10期（2021年04月22号）',
    ],
    'PHP就业班' => [
        '北京-第36期（2021年04月07号）',
        '广州-第09期（2021年04月06号）',
    ],
    'PHP远程班' => [
        '基础班-第35期（2021年03月05号）',
        '就业班-第36期（2021年04月07号）',
    ],
];
?>
<h2>PHP培训开班信息</h2>
<?php foreach ($classes as $title => $items): ?>
    <p><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></p>
    <ul>
        <?php foreach ($items as $item): ?>
            <li><?php echo htmlspecialchars($item, ENT_QUOTES, 'UTF-8'); ?></li>
        <?php endforeach; ?>
    </ul>
<?php endforeach; ?>
