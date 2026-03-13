<?php
$products = [
    [
        'name' => '智能手机',
        'image' => 'smartphone.jpg',
        'price' => '￥1999',
        'desc' => '6.5英寸全面屏，128GB存储，5000mAh大电池。'
    ],
    [
        'name' => '轻薄笔记本',
        'image' => 'laptop.jpg',
        'price' => '￥5299',
        'desc' => '14英寸高清屏，16GB内存，适合办公与学习。'
    ],
    [
        'name' => '无线蓝牙耳机',
        'image' => 'earphone.jpg',
        'price' => '￥299',
        'desc' => '支持主动降噪，续航长达24小时，佩戴舒适。'
    ]
];
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>商品信息展示</title>
    <style>
        body {
            font-family: "Microsoft YaHei", sans-serif;
            background: #f5f7fa;
        }
        table {
            width: 80%;
            margin: 30px auto;
            border-collapse: collapse;
            background: #fff;
        }
        caption {
            font-size: 24px;
            color: #1f5fbf;
            font-weight: bold;
            margin-bottom: 12px;
        }
        th, td {
            border: 1px solid #d9d9d9;
            padding: 12px;
            text-align: center;
        }
        th {
            background: #f0f4ff;
        }
        img {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 6px;
        }
        .desc {
            text-align: left;
        }
    </style>
</head>
<body>
    <table>
        <caption>XXX购物网站商品信息表</caption>
        <tr>
            <th>商品名称</th>
            <th>商品图片</th>
            <th>价格</th>
            <th>商品简介</th>
        </tr>
        <?php foreach ($products as $product): ?>
        <tr>
            <td><?php echo htmlspecialchars($product['name']); ?></td>
            <td>
                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            </td>
            <td><?php echo htmlspecialchars($product['price']); ?></td>
            <td class="desc"><?php echo htmlspecialchars($product['desc']); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>