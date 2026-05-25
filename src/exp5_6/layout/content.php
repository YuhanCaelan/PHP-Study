<?php
if (!defined('APP')) {
    exit('error!');
}

$cards = [
    ['img' => '/exp5_6/实验相关代码/img/php.jpg', 'label' => 'PHP基础班'],
    ['img' => '/exp5_6/实验相关代码/img/java.jpg', 'label' => 'Java EE基础班'],
    ['img' => '/exp5_6/实验相关代码/img/ps.jpg', 'label' => 'Photoshop基础班'],
    ['img' => '/exp5_6/实验相关代码/img/oc.jpg', 'label' => 'Objective-C基础班'],
    ['img' => '/exp5_6/实验相关代码/img/android.jpg', 'label' => 'Android基础班'],
    ['img' => '/exp5_6/实验相关代码/img/sql.jpg', 'label' => 'MySQL基础班'],
];
?>
<div class="lst">
    <?php foreach ($cards as $card): ?>
        <div class="pic">
            <img src="<?php echo htmlspecialchars($card['img'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($card['label'], ENT_QUOTES, 'UTF-8'); ?>">
            <div class="pic-title"><?php echo htmlspecialchars($card['label'], ENT_QUOTES, 'UTF-8'); ?></div>
        </div>
    <?php endforeach; ?>
</div>
