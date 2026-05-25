<?php
declare(strict_types=1);

require __DIR__ . '/db.php';

$conn = getDb();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$错误信息 = [];
$提示信息 = '';
$编辑课程 = null;

function 查询课程(mysqli $conn, int $id): ?array
{
    $stmt = $conn->prepare('SELECT id, name, xf, xq FROM kcb WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc() ?: null;
    $stmt->close();

    return $row;
}

$action = trim((string) ($_GET['action'] ?? ''));
$id = (int) ($_GET['id'] ?? 0);

if ($action === 'delete' && $id > 0) {
    $stmt = $conn->prepare('DELETE FROM kcb WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();

    header('Location: example3_link.php?msg=deleted');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['op'] ?? '') === 'save') {
    $课程号 = (int) ($_POST['id'] ?? 0);
    $课程名 = trim((string) ($_POST['name'] ?? ''));
    $学分文本 = trim((string) ($_POST['xf'] ?? ''));
    $学期文本 = trim((string) ($_POST['xq'] ?? ''));

    if ($课程号 <= 0) {
        $错误信息[] = '课程号不合法';
    }
    if ($课程名 === '') {
        $错误信息[] = '课程名不能为空';
    } elseif (mb_strlen($课程名) > 10) {
        $错误信息[] = '课程名长度不能超过10';
    }

    if (filter_var($学分文本, FILTER_VALIDATE_INT) === false) {
        $错误信息[] = '学分必须是整数';
    }
    if (filter_var($学期文本, FILTER_VALIDATE_INT) === false) {
        $错误信息[] = '学期必须是整数';
    }

    if (empty($错误信息)) {
        $学分 = (int) $学分文本;
        $学期 = (int) $学期文本;

        $stmt = $conn->prepare('UPDATE kcb SET name = ?, xf = ?, xq = ? WHERE id = ?');
        $stmt->bind_param('siii', $课程名, $学分, $学期, $课程号);
        $stmt->execute();
        $stmt->close();

        header('Location: example3_link.php?msg=updated');
        exit;
    }

    $编辑课程 = [
        'id' => $课程号,
        'name' => $课程名,
        'xf' => $学分文本,
        'xq' => $学期文本,
    ];
}

if ($action === 'edit' && $id > 0) {
    $编辑课程 = 查询课程($conn, $id);
    if ($编辑课程 === null) {
        $错误信息[] = '未找到要更新的课程';
    }
}

$msg = trim((string) ($_GET['msg'] ?? ''));
if ($msg === 'deleted') {
    $提示信息 = '删除成功';
}
if ($msg === 'updated') {
    $提示信息 = '更新成功';
}

$课程列表 = [];
$result = $conn->query('SELECT id, name, xf, xq FROM kcb ORDER BY id ASC');
while ($row = $result->fetch_assoc()) {
    $课程列表[] = $row;
}
$result->free();

$conn->close();
?>
<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>课程表超链接操作</title>
    <style>
        body {
            margin: 0;
            font-family: "Microsoft YaHei", "PingFang SC", sans-serif;
            background: #ffffff;
            color: #222;
        }

        .容器 {
            width: 700px;
            margin: 24px auto;
        }

        .标题 {
            font-size: 24px;
            margin-bottom: 12px;
        }

        .提示 {
            color: #0d6b2f;
            margin-bottom: 8px;
        }

        .错误 {
            color: #c62828;
            margin-bottom: 8px;
            line-height: 1.6;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #eef8fd;
            border: 1px solid #b9d9e8;
            font-size: 16px;
        }

        th, td {
            border: 1px solid #b9d9e8;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background: #ddeff7;
        }

        a {
            color: #1a63c8;
            text-decoration: underline;
        }

        .编辑框 {
            margin-top: 14px;
            border: 1px solid #cfcfcf;
            padding: 10px;
            background: #f8f8f8;
        }

        .编辑框 h3 {
            margin: 0 0 10px;
            font-size: 18px;
        }

        .行 {
            display: grid;
            grid-template-columns: 90px 1fr;
            margin-bottom: 8px;
            gap: 8px;
            align-items: center;
        }

        .行 input {
            height: 30px;
            font-size: 16px;
            padding: 2px 6px;
            box-sizing: border-box;
        }

        .按钮区 {
            display: flex;
            gap: 8px;
        }

        button {
            height: 32px;
            padding: 0 12px;
            font-size: 15px;
        }
    </style>
</head>
<body>
<div class="容器">
    <div class="标题">课程信息管理（超链接操作）</div>

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

    <table>
        <thead>
        <tr>
            <th>课程号</th>
            <th>课程名</th>
            <th>学分</th>
            <th>学期</th>
            <th>删除</th>
            <th>更新</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($课程列表 as $课程): ?>
            <tr>
                <td><?= (int) $课程['id'] ?></td>
                <td><?= htmlspecialchars((string) $课程['name'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= (int) $课程['xf'] ?></td>
                <td><?= (int) $课程['xq'] ?></td>
                <td><a href="?action=delete&id=<?= (int) $课程['id'] ?>" onclick="return confirm('确定删除该课程吗？')">删除</a></td>
                <td><a href="?action=edit&id=<?= (int) $课程['id'] ?>">更新</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <?php if ($编辑课程 !== null): ?>
        <form method="post" class="编辑框">
            <h3>更新课程：<?= (int) $编辑课程['id'] ?></h3>
            <input type="hidden" name="op" value="save">
            <input type="hidden" name="id" value="<?= (int) $编辑课程['id'] ?>">

            <div class="行">
                <label>课程号</label>
                <input type="text" value="<?= (int) $编辑课程['id'] ?>" disabled>
            </div>
            <div class="行">
                <label>课程名</label>
                <input type="text" name="name" maxlength="10" value="<?= htmlspecialchars((string) $编辑课程['name'], ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="行">
                <label>学分</label>
                <input type="text" name="xf" value="<?= htmlspecialchars((string) $编辑课程['xf'], ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="行">
                <label>学期</label>
                <input type="text" name="xq" value="<?= htmlspecialchars((string) $编辑课程['xq'], ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="按钮区">
                <button type="submit">保存更新</button>
                <a href="example3_link.php">取消</a>
            </div>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
