<?php
declare(strict_types=1);

require __DIR__ . '/db.php';

$conn = getDb();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$错误信息 = [];
$提示信息 = '';
$表单数据 = [
    'id' => '',
    'name' => '',
    'xf' => '',
    'xq' => '',
];

function 读取课程(mysqli $conn, int $id): ?array
{
    $stmt = $conn->prepare('SELECT id, name, xf, xq FROM kcb WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc() ?: null;
    $stmt->close();

    return $row;
}

function 读取整数(string $值, string $字段名, array &$错误信息): ?int
{
    $值 = trim($值);
    if ($值 === '' || filter_var($值, FILTER_VALIDATE_INT) === false) {
        $错误信息[] = $字段名 . '必须是整数';
        return null;
    }

    return (int) $值;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $操作 = trim((string) ($_POST['op'] ?? ''));

    $表单数据['id'] = trim((string) ($_POST['id'] ?? ''));
    $表单数据['name'] = trim((string) ($_POST['name'] ?? ''));
    $表单数据['xf'] = trim((string) ($_POST['xf'] ?? ''));
    $表单数据['xq'] = trim((string) ($_POST['xq'] ?? ''));

    $课程号 = 读取整数($表单数据['id'], '课程号', $错误信息);

    if ($操作 === '查找' && $课程号 !== null) {
        $课程 = 读取课程($conn, $课程号);
        if ($课程 === null) {
            $错误信息[] = '未找到该课程号对应的课程';
        } else {
            $表单数据['id'] = (string) $课程['id'];
            $表单数据['name'] = (string) $课程['name'];
            $表单数据['xf'] = (string) $课程['xf'];
            $表单数据['xq'] = (string) $课程['xq'];
            $提示信息 = '查询成功';
        }
    }

    if ($操作 === '添加' || $操作 === '修改') {
        if ($表单数据['name'] === '') {
            $错误信息[] = '课程名不能为空';
        } elseif (mb_strlen($表单数据['name']) > 10) {
            $错误信息[] = '课程名长度不能超过10个字符';
        }

        $学分 = 读取整数($表单数据['xf'], '学分', $错误信息);
        $学期 = 读取整数($表单数据['xq'], '学期', $错误信息);

        if ($课程号 !== null && $学分 !== null && $学期 !== null && empty($错误信息)) {
            $已有课程 = 读取课程($conn, $课程号);

            if ($操作 === '添加') {
                if ($已有课程 !== null) {
                    $错误信息[] = '课程号已存在，不能重复添加';
                } else {
                    $stmt = $conn->prepare('INSERT INTO kcb (id, name, xf, xq) VALUES (?, ?, ?, ?)');
                    $stmt->bind_param('isii', $课程号, $表单数据['name'], $学分, $学期);
                    $stmt->execute();
                    $stmt->close();
                    $提示信息 = '添加成功';
                }
            }

            if ($操作 === '修改') {
                if ($已有课程 === null) {
                    $错误信息[] = '课程不存在，无法修改';
                } else {
                    $stmt = $conn->prepare('UPDATE kcb SET name = ?, xf = ?, xq = ? WHERE id = ?');
                    $stmt->bind_param('siii', $表单数据['name'], $学分, $学期, $课程号);
                    $stmt->execute();
                    $stmt->close();
                    $提示信息 = '修改成功';
                }
            }
        }
    }

    if ($操作 === '删除' && $课程号 !== null && empty($错误信息)) {
        $已有课程 = 读取课程($conn, $课程号);
        if ($已有课程 === null) {
            $错误信息[] = '课程不存在，无法删除';
        } else {
            $stmt = $conn->prepare('DELETE FROM kcb WHERE id = ?');
            $stmt->bind_param('i', $课程号);
            $stmt->execute();
            $stmt->close();
            $提示信息 = '删除成功';

            $表单数据 = [
                'id' => '',
                'name' => '',
                'xf' => '',
                'xq' => '',
            ];
        }
    }
}

$所有课程 = [];
$result = $conn->query('SELECT id, name, xf, xq FROM kcb ORDER BY id ASC');
while ($row = $result->fetch_assoc()) {
    $所有课程[] = $row;
}
$result->free();

$conn->close();
?>
<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>课程表操作</title>
    <style>
        body {
            margin: 0;
            font-family: "Microsoft YaHei", "PingFang SC", sans-serif;
            background: #ececec;
            color: #111;
        }

        .容器 {
            width: 600px;
            margin: 20px auto;
            text-align: center;
        }

        .标题 {
            font-size: 30px;
            margin-bottom: 25px;
        }

        .查询行 {
            margin-bottom: 16px;
            font-size: 30px;
        }

        .查询行 input {
            width: 120px;
            height: 32px;
            font-size: 20px;
            margin: 0 10px;
            text-align: center;
        }

        .查询行 button {
            font-size: 22px;
            padding: 2px 10px;
        }

        .表单框 {
            width: 520px;
            margin: 0 auto;
            border: 1px solid #bbb;
            background: #e7e7e7;
        }

        .行 {
            display: grid;
            grid-template-columns: 160px 1fr;
            border-bottom: 1px solid #bbb;
            font-size: 34px;
            line-height: 1.5;
        }

        .行:last-of-type {
            border-bottom: none;
        }

        .标签 {
            text-align: left;
            padding-left: 8px;
            border-right: 1px solid #bbb;
        }

        .输入区 {
            padding: 4px;
        }

        .输入区 input {
            border-collapse: collapse;
            width: 100%;
            height: 44px;
            font-size: 30px;
            border: 1px solid #aaa;
            box-sizing: border-box;
            padding: 0 8px;
        }

        .按钮行 {
            padding: 8px 0;
        }

        .按钮行 button {
            font-size: 30px;
            margin: 0 6px;
            padding: 3px 14px;
        }

        .提示 {
            margin: 10px auto;
            color: #0b5f20;
            font-size: 24px;
        }

        .错误 {
            margin: 10px auto;
            color: #b00020;
            font-size: 22px;
            line-height: 1.6;
        }

        .数据表 {
            margin: 18px auto 0;
            width: 520px;
            border-collapse: collapse;
            background: #fff;
            font-size: 22px;
        }

        .数据表 th,
        .数据表 td {
            border: 1px solid #bbb;
            padding: 6px;
        }

        @media (max-width: 640px) {
            .容器,
            .表单框,
            .数据表 {
                width: 95%;
            }

            .标题,
            .查询行,
            .行,
            .按钮行 button {
                font-size: 22px;
            }

            .输入区 input {
                height: 34px;
                font-size: 20px;
            }

            .行 {
                grid-template-columns: 110px 1fr;
            }
        }
    </style>
</head>
<body>
<div class="容器">
    <div class="标题">课程表操作</div>

    <form method="post" class="查询行">
        根据课程号查询：
        <input type="text" name="id" value="<?= htmlspecialchars($表单数据['id'], ENT_QUOTES, 'UTF-8') ?>">
        <button type="submit" name="op" value="查找">查找</button>
    </form>

    <?php if ($提示信息 !== ''): ?>
        <div class="提示"><?= htmlspecialchars($提示信息, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if (!empty($错误信息)): ?>
        <div class="错误">
            <?php foreach ($错误信息 as $err): ?>
                <div><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" class="表单框">
        <div class="行">
            <div class="标签">课程号：</div>
            <div class="输入区"><input type="text" name="id" value="<?= htmlspecialchars($表单数据['id'], ENT_QUOTES, 'UTF-8') ?>"></div>
        </div>
        <div class="行">
            <div class="标签">课程名：</div>
            <div class="输入区"><input type="text" name="name" maxlength="10" value="<?= htmlspecialchars($表单数据['name'], ENT_QUOTES, 'UTF-8') ?>"></div>
        </div>
        <div class="行">
            <div class="标签">学分：</div>
            <div class="输入区"><input type="text" name="xf" value="<?= htmlspecialchars($表单数据['xf'], ENT_QUOTES, 'UTF-8') ?>"></div>
        </div>
        <div class="行">
            <div class="标签">学期：</div>
            <div class="输入区"><input type="text" name="xq" value="<?= htmlspecialchars($表单数据['xq'], ENT_QUOTES, 'UTF-8') ?>"></div>
        </div>
        <div class="按钮行">
            <button type="submit" name="op" value="修改">修改</button>
            <button type="submit" name="op" value="添加">添加</button>
            <button type="submit" name="op" value="删除" onclick="return confirm('确认删除该课程吗？')">删除</button>
        </div>
    </form>

    <table class="数据表">
        <thead>
        <tr>
            <th>课程号</th>
            <th>课程名</th>
            <th>学分</th>
            <th>学期</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($所有课程 as $课程): ?>
            <tr>
                <td><?= (int) $课程['id'] ?></td>
                <td><?= htmlspecialchars((string) $课程['name'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= (int) $课程['xf'] ?></td>
                <td><?= (int) $课程['xq'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
