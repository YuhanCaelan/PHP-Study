<?php
$products = [
    ['name' => '主板', 'price' => 370, 'origin' => '广东', 'qty' => 2],
    ['name' => '显卡', 'price' => 700, 'origin' => '北京', 'qty' => 2],
    ['name' => '硬盘', 'price' => 500, 'origin' => '上海', 'qty' => 4],
];

$grandTotal = 0;
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>练习1-订货单</title>
    <style>
        body { font-family: "Microsoft YaHei", sans-serif; background: #f0f0f0; margin: 0; }
        .wrap { width: 820px; margin: 22px auto; background: #ececec; border: 1px solid #cfcfcf; border-radius: 4px; padding: 10px 12px; }
        h2 { text-align: center; margin: 0 0 8px; }
        table { width: 100%; border-collapse: collapse; text-align: center; color: #333; }
        th, td { border: 1px solid #bfc6ce; padding: 8px; }
        thead tr { background: #d3deea; }
        tfoot td { text-align: right; padding-right: 16px; }
        .money { color: #e00000; font-weight: bold; }
    </style>
</head>
<body>
<div class="wrap">
    <h2>商品订货单</h2>
    <table>
        <thead>
            <tr>
                <th>商品名称</th>
                <th>单价(元)</th>
                <th>产地</th>
                <th>数量(个)</th>
                <th>总价(元)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $item): ?>
                <?php $total = $item['price'] * $item['qty']; ?>
                <?php $grandTotal += $total; ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo $item['price']; ?></td>
                    <td><?php echo htmlspecialchars($item['origin'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo $item['qty']; ?></td>
                    <td><?php echo $total; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5">小计：<span class="money"><?php echo $grandTotal; ?>元</span></td>
            </tr>
        </tfoot>
    </table>
</div>
</body>
</html>
