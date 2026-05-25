<?php
declare(strict_types=1);

require __DIR__ . '/db.php';

$conn = getDb();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conn->query(
    "CREATE TABLE IF NOT EXISTS employees (
        id INT(11) NOT NULL AUTO_INCREMENT,
        name VARCHAR(30) NOT NULL,
        dept VARCHAR(30) NOT NULL,
        birth_date DATETIME NOT NULL,
        hire_date DATETIME NOT NULL,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
);

$countRes = $conn->query('SELECT COUNT(*) AS c FROM employees');
$countRow = $countRes->fetch_assoc();
$countRes->free();
if ((int) $countRow['c'] === 0) {
    $conn->query(
        "INSERT INTO employees (name, dept, birth_date, hire_date) VALUES
        ('许学艺', '财务部', '1950-10-10 00:00:00', '1951-12-21 09:00:00'),
        ('刘绮华', '外事总', '1953-12-03 10:00:00', '1982-02-16 09:00:00'),
        ('王慧', '人事部', '1970-10-02 20:10:00', '1992-06-20 09:00:00'),
        ('郭晓洁', '销售部', '1990-10-07 07:05:00', '1992-03-16 09:00:00'),
        ('蔡科木', '某待部', '2021-01-04 22:02:00', '2021-04-20 22:00:00')"
    );
}

$errors = [];
$message = '';
$action = trim((string) ($_GET['action'] ?? 'list'));
$id = (int) ($_GET['id'] ?? 0);
$keyword = trim((string) ($_GET['keyword'] ?? ''));

$formData = [
    'id' => '',
    'name' => '',
    'dept' => '',
    'birth_date' => '',
    'hire_date' => '',
];

function findEmployee(mysqli $conn, int $id): ?array
{
    $stmt = $conn->prepare('SELECT id, name, dept, birth_date, hire_date FROM employees WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc() ?: null;
    $stmt->close();

    return $row;
}

function validDateTime(string $value): bool
{
    $dt = DateTime::createFromFormat('Y-m-d H:i:s', $value);
    return $dt !== false && $dt->format('Y-m-d H:i:s') === $value;
}

if ($action === 'delete' && $id > 0) {
    $stmt = $conn->prepare('DELETE FROM employees WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();

    header('Location: employee_manage.php?msg=deleted');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $op = trim((string) ($_POST['op'] ?? ''));

    $formData['id'] = trim((string) ($_POST['id'] ?? ''));
    $formData['name'] = trim((string) ($_POST['name'] ?? ''));
    $formData['dept'] = trim((string) ($_POST['dept'] ?? ''));
    $formData['birth_date'] = trim((string) ($_POST['birth_date'] ?? ''));
    $formData['hire_date'] = trim((string) ($_POST['hire_date'] ?? ''));

    if ($formData['name'] === '') {
        $errors[] = '姓名不能为空';
    }
    if ($formData['dept'] === '') {
        $errors[] = '所属部门不能为空';
    }
    if (!validDateTime($formData['birth_date'])) {
        $errors[] = '出生日期格式必须为 YYYY-MM-DD HH:MM:SS';
    }
    if (!validDateTime($formData['hire_date'])) {
        $errors[] = '入职时间格式必须为 YYYY-MM-DD HH:MM:SS';
    }

    if ($op === 'add') {
        if (empty($errors)) {
            $stmt = $conn->prepare('INSERT INTO employees (name, dept, birth_date, hire_date) VALUES (?, ?, ?, ?)');
            $stmt->bind_param('ssss', $formData['name'], $formData['dept'], $formData['birth_date'], $formData['hire_date']);
            $stmt->execute();
            $stmt->close();

            header('Location: employee_manage.php?msg=added');
            exit;
        }
        $action = 'add';
    }

    if ($op === 'edit') {
        $editId = (int) $formData['id'];
        if ($editId <= 0) {
            $errors[] = '员工ID不合法';
        }

        if (empty($errors)) {
            $stmt = $conn->prepare('UPDATE employees SET name = ?, dept = ?, birth_date = ?, hire_date = ? WHERE id = ?');
            $stmt->bind_param('ssssi', $formData['name'], $formData['dept'], $formData['birth_date'], $formData['hire_date'], $editId);
            $stmt->execute();
            $stmt->close();

            header('Location: employee_manage.php?msg=updated');
            exit;
        }
        $action = 'edit';
        $id = (int) $formData['id'];
    }
}

if ($action === 'edit' && $id > 0 && $formData['id'] === '') {
    $row = findEmployee($conn, $id);
    if ($row === null) {
        $errors[] = '未找到该员工';
        $action = 'list';
    } else {
        $formData['id'] = (string) $row['id'];
        $formData['name'] = (string) $row['name'];
        $formData['dept'] = (string) $row['dept'];
        $formData['birth_date'] = (string) $row['birth_date'];
        $formData['hire_date'] = (string) $row['hire_date'];
    }
}

$msg = trim((string) ($_GET['msg'] ?? ''));
if ($msg === 'added') {
    $message = '添加成功';
}
if ($msg === 'updated') {
    $message = '更新成功';
}
if ($msg === 'deleted') {
    $message = '删除成功';
}

$employees = [];
$searchStmt = null;
if ($keyword === '') {
    $result = $conn->query('SELECT id, name, dept, birth_date, hire_date FROM employees ORDER BY id ASC');
} else {
    $like = '%' . $keyword . '%';
    $searchStmt = $conn->prepare('SELECT id, name, dept, birth_date, hire_date FROM employees WHERE name LIKE ? ORDER BY id ASC');
    $searchStmt->bind_param('s', $like);
    $searchStmt->execute();
    $result = $searchStmt->get_result();
}

while ($row = $result->fetch_assoc()) {
    $employees[] = $row;
}
$result->free();
if ($searchStmt instanceof mysqli_stmt) {
    $searchStmt->close();
}

$conn->close();
?>
<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>员工信息列表</title>
    <style>
        body {
            margin: 0;
            font-family: "Microsoft YaHei", "PingFang SC", sans-serif;
            background: #fff;
            color: #222;
        }

        .container {
            width: 900px;
            margin: 24px auto;
        }

        .title {
            text-align: center;
            font-size: 28px;
            margin-bottom: 12px;
        }

        .search-bar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .search-bar input {
            height: 26px;
            width: 130px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #eef8fd;
            border: 1px solid #b8d9e7;
            font-size: 13px;
        }

        th,
        td {
            border: 1px solid #b8d9e7;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background: #dfeff7;
            text-align: center;
        }

        .ops a {
            color: #1f65c5;
            margin-right: 8px;
            text-decoration: underline;
        }

        .ops a.delete {
            color: #c62828;
        }

        .toolbar {
            text-align: right;
            margin-top: 6px;
            font-size: 13px;
        }

        .toolbar a {
            color: #1f65c5;
            text-decoration: underline;
        }

        .message {
            color: #0e6b30;
            margin: 6px 0;
            font-size: 14px;
        }

        .errors {
            color: #c62828;
            margin: 6px 0;
            font-size: 14px;
            line-height: 1.8;
        }

        .panel {
            margin-top: 16px;
            border: 1px solid #d0d0d0;
            padding: 12px;
            background: #fafafa;
        }

        .panel h3 {
            margin: 0 0 10px;
            font-size: 18px;
        }

        .row {
            display: grid;
            grid-template-columns: 110px 1fr;
            gap: 8px;
            margin-bottom: 8px;
            align-items: center;
        }

        .row input {
            height: 30px;
            padding: 0 8px;
            font-size: 14px;
        }

        .btns {
            display: flex;
            gap: 8px;
        }

        button {
            height: 30px;
            padding: 0 12px;
            font-size: 14px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="title">员工信息列表</div>

    <form method="get" class="search-bar">
        <label for="keyword">姓名查询：</label>
        <input id="keyword" name="keyword" type="text" value="<?= htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8') ?>">
        <button type="submit">搜索</button>
    </form>

    <?php if ($message !== ''): ?>
        <div class="message"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="errors">
            <?php foreach ($errors as $err): ?>
                <div><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>姓名</th>
            <th>所属部门</th>
            <th>出生日期</th>
            <th>入职时间</th>
            <th>链接操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($employees as $emp): ?>
            <tr>
                <td><?= (int) $emp['id'] ?></td>
                <td><?= htmlspecialchars((string) $emp['name'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string) $emp['dept'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string) $emp['birth_date'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string) $emp['hire_date'], ENT_QUOTES, 'UTF-8') ?></td>
                <td class="ops">
                    <a href="?action=edit&id=<?= (int) $emp['id'] ?>&keyword=<?= urlencode($keyword) ?>">编辑</a>
                    <a class="delete" href="?action=delete&id=<?= (int) $emp['id'] ?>&keyword=<?= urlencode($keyword) ?>" onclick="return confirm('确认删除该员工吗？')">删除</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div class="toolbar">
        <a href="?action=add&keyword=<?= urlencode($keyword) ?>">添加员工</a>
    </div>

    <?php if ($action === 'add' || $action === 'edit'): ?>
        <form method="post" class="panel">
            <h3><?= $action === 'add' ? '添加员工' : '编辑员工' ?></h3>
            <input type="hidden" name="op" value="<?= $action === 'add' ? 'add' : 'edit' ?>">
            <input type="hidden" name="id" value="<?= htmlspecialchars($formData['id'], ENT_QUOTES, 'UTF-8') ?>">

            <?php if ($action === 'edit'): ?>
                <div class="row">
                    <label>员工ID</label>
                    <input type="text" value="<?= htmlspecialchars($formData['id'], ENT_QUOTES, 'UTF-8') ?>" disabled>
                </div>
            <?php endif; ?>

            <div class="row">
                <label for="name">姓名</label>
                <input id="name" name="name" type="text" value="<?= htmlspecialchars($formData['name'], ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="row">
                <label for="dept">所属部门</label>
                <input id="dept" name="dept" type="text" value="<?= htmlspecialchars($formData['dept'], ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="row">
                <label for="birth_date">出生日期</label>
                <input id="birth_date" name="birth_date" type="text" placeholder="YYYY-MM-DD HH:MM:SS" value="<?= htmlspecialchars($formData['birth_date'], ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="row">
                <label for="hire_date">入职时间</label>
                <input id="hire_date" name="hire_date" type="text" placeholder="YYYY-MM-DD HH:MM:SS" value="<?= htmlspecialchars($formData['hire_date'], ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="btns">
                <button type="submit">保存</button>
                <a href="employee_manage.php">取消</a>
            </div>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
