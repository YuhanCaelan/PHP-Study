<?php
declare(strict_types=1);

require __DIR__ . '/../db.php';

$conn = getDb();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$storageRoot = sys_get_temp_dir() . '/exp7_8_netdisk_storage';
if (!is_dir($storageRoot)) {
    mkdir($storageRoot, 0777, true);
}
if (!is_dir($storageRoot . '/0')) {
    mkdir($storageRoot . '/0', 0777, true);
}

$conn->query(
    "CREATE TABLE IF NOT EXISTS netdisk_folder (
        folder_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
        folder_name VARCHAR(255) NOT NULL,
        folder_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        folder_path VARCHAR(255) NOT NULL,
        folder_pid INT(10) UNSIGNED NOT NULL,
        PRIMARY KEY (folder_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
);

$conn->query(
    "CREATE TABLE IF NOT EXISTS netdisk_file (
        file_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
        file_name VARCHAR(255) NOT NULL,
        file_save VARCHAR(255) NOT NULL,
        file_size INT(10) UNSIGNED NOT NULL,
        file_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        folder_id INT(10) UNSIGNED NOT NULL,
        PRIMARY KEY (file_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
);

$msg = '';
$err = '';
$fid = max(0, (int) ($_GET['fid'] ?? 0));
$action = (string) ($_GET['action'] ?? '');

function getFolder(mysqli $conn, int $fid): ?array
{
    if ($fid === 0) {
        return [
            'folder_id' => 0,
            'folder_name' => '主目录',
            'folder_pid' => 0,
            'folder_path' => '0',
        ];
    }

    $stmt = $conn->prepare('SELECT folder_id, folder_name, folder_pid, folder_path FROM netdisk_folder WHERE folder_id = ?');
    $stmt->bind_param('i', $fid);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc() ?: null;
    $stmt->close();

    return $row;
}

function folderStoragePath(string $storageRoot, int $fid): string
{
    return $storageRoot . '/' . $fid;
}

$currentFolder = getFolder($conn, $fid);
if ($currentFolder === null) {
    $fid = 0;
    $currentFolder = getFolder($conn, 0);
}

if ($action === 'delete_folder') {
    $delId = (int) ($_GET['id'] ?? 0);
    if ($delId <= 0) {
        $err = '目录参数无效';
    } else {
        $stmt = $conn->prepare('SELECT COUNT(*) c FROM netdisk_folder WHERE folder_pid = ?');
        $stmt->bind_param('i', $delId);
        $stmt->execute();
        $subCnt = (int) ($stmt->get_result()->fetch_assoc()['c'] ?? 0);
        $stmt->close();

        $stmt = $conn->prepare('SELECT COUNT(*) c FROM netdisk_file WHERE folder_id = ?');
        $stmt->bind_param('i', $delId);
        $stmt->execute();
        $fileCnt = (int) ($stmt->get_result()->fetch_assoc()['c'] ?? 0);
        $stmt->close();

        if ($subCnt > 0 || $fileCnt > 0) {
            $err = '目录非空，不能删除';
        } else {
            $stmt = $conn->prepare('DELETE FROM netdisk_folder WHERE folder_id = ?');
            $stmt->bind_param('i', $delId);
            $stmt->execute();
            $stmt->close();

            $diskPath = folderStoragePath($storageRoot, $delId);
            if (is_dir($diskPath)) {
                @rmdir($diskPath);
            }

            header('Location: index.php?fid=' . $fid . '&msg=folder_deleted');
            exit;
        }
    }
}

if ($action === 'delete_file') {
    $fileId = (int) ($_GET['id'] ?? 0);
    if ($fileId > 0) {
        $stmt = $conn->prepare('SELECT file_save FROM netdisk_file WHERE file_id = ?');
        $stmt->bind_param('i', $fileId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($row !== null) {
            $filePath = $storageRoot . '/' . $row['file_save'];
            if (is_file($filePath)) {
                @unlink($filePath);
            }

            $stmt = $conn->prepare('DELETE FROM netdisk_file WHERE file_id = ?');
            $stmt->bind_param('i', $fileId);
            $stmt->execute();
            $stmt->close();

            header('Location: index.php?fid=' . $fid . '&msg=file_deleted');
            exit;
        }
    }
    $err = '文件不存在';
}

if ($action === 'download') {
    $fileId = (int) ($_GET['id'] ?? 0);
    $stmt = $conn->prepare('SELECT file_name, file_save FROM netdisk_file WHERE file_id = ?');
    $stmt->bind_param('i', $fileId);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($row !== null) {
        $path = $storageRoot . '/' . $row['file_save'];
        if (is_file($path)) {
            header('Content-Type: application/octet-stream');
            header('Content-Length: ' . filesize($path));
            header('Content-Disposition: attachment; filename="' . rawurlencode((string) $row['file_name']) . '"');
            readfile($path);
            $conn->close();
            exit;
        }
    }
    $err = '下载文件不存在';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $op = (string) ($_POST['op'] ?? '');
    $fid = max(0, (int) ($_POST['fid'] ?? $fid));

    if ($op === 'create_folder') {
        $folderName = trim((string) ($_POST['folder_name'] ?? ''));
        if ($folderName === '') {
            $err = '文件夹名不能为空';
        } else {
            $parent = getFolder($conn, $fid);
            if ($parent === null) {
                $err = '父目录不存在';
            } else {
                $parentPath = (string) $parent['folder_path'];
                $newPath = $parentPath === '0' ? (string) $fid : ($parentPath . '/' . $fid);

                $stmt = $conn->prepare('INSERT INTO netdisk_folder(folder_name, folder_path, folder_pid) VALUES (?, ?, ?)');
                $stmt->bind_param('ssi', $folderName, $newPath, $fid);
                $stmt->execute();
                $newId = $stmt->insert_id;
                $stmt->close();

                $newStorage = folderStoragePath($storageRoot, (int) $newId);
                if (!is_dir($newStorage)) {
                    mkdir($newStorage, 0777, true);
                }
                header('Location: index.php?fid=' . $fid . '&msg=folder_added');
                exit;
            }
        }
    }

    if ($op === 'upload_file') {
        $file = $_FILES['upload_file'] ?? null;
        if (!is_array($file) || (int) ($file['error'] ?? 1) !== UPLOAD_ERR_OK) {
            $err = '请选择有效文件';
        } else {
            $originName = basename((string) $file['name']);
            $saveName = $fid . '/' . uniqid('f_', true) . '_' . $originName;
            $savePath = $storageRoot . '/' . $saveName;

            $folderDisk = folderStoragePath($storageRoot, $fid);
            if (!is_dir($folderDisk)) {
                mkdir($folderDisk, 0777, true);
            }

            if (!move_uploaded_file((string) $file['tmp_name'], $savePath)) {
                $err = '文件保存失败';
            } else {
                $size = (int) filesize($savePath);
                $stmt = $conn->prepare('INSERT INTO netdisk_file(file_name, file_save, file_size, folder_id) VALUES (?, ?, ?, ?)');
                $stmt->bind_param('ssii', $originName, $saveName, $size, $fid);
                $stmt->execute();
                $stmt->close();

                header('Location: index.php?fid=' . $fid . '&msg=file_added');
                exit;
            }
        }
    }
}

$status = (string) ($_GET['msg'] ?? '');
$map = [
    'folder_added' => '文件夹创建成功',
    'folder_deleted' => '文件夹删除成功',
    'file_added' => '文件上传成功',
    'file_deleted' => '文件删除成功',
];
if (isset($map[$status])) {
    $msg = $map[$status];
}

$folderRows = [];
$stmt = $conn->prepare('SELECT folder_id, folder_name, folder_time FROM netdisk_folder WHERE folder_pid = ? ORDER BY folder_id ASC');
$stmt->bind_param('i', $fid);
$stmt->execute();
$res = $stmt->get_result();
while ($r = $res->fetch_assoc()) {
    $folderRows[] = $r;
}
$stmt->close();

$fileRows = [];
$stmt = $conn->prepare('SELECT file_id, file_name, file_size, file_time FROM netdisk_file WHERE folder_id = ? ORDER BY file_id ASC');
$stmt->bind_param('i', $fid);
$stmt->execute();
$res = $stmt->get_result();
while ($r = $res->fetch_assoc()) {
    $fileRows[] = $r;
}
$stmt->close();

$parentFid = (int) ($currentFolder['folder_pid'] ?? 0);

$conn->close();
?>
<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>在线网盘</title>
    <style>
        body { font-family: "Microsoft YaHei", sans-serif; margin: 16px; }
        .msg { color: #0a6e2f; }
        .err { color: #c62828; }
        .bar { margin-bottom: 10px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #cfcfcf; padding: 6px 8px; }
        th { background: #eef4fc; }
    </style>
</head>
<body>
<h2>在线网盘</h2>
<div><a href="../exp7_8_tasks.php">返回任务页</a></div>
<div class="bar">当前位置：fid=<?= (int) $fid ?>（<?= htmlspecialchars((string) ($currentFolder['folder_name'] ?? '主目录'), ENT_QUOTES, 'UTF-8') ?>）</div>
<?php if ($fid !== 0): ?><div class="bar"><a href="?fid=<?= (int) $parentFid ?>">返回上一级</a></div><?php endif; ?>
<?php if ($msg !== ''): ?><div class="msg"><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
<?php if ($err !== ''): ?><div class="err"><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>

<form method="post" class="bar">
    <input type="hidden" name="op" value="create_folder">
    <input type="hidden" name="fid" value="<?= (int) $fid ?>">
    新建文件夹：<input type="text" name="folder_name" required>
    <button type="submit">创建</button>
</form>

<form method="post" enctype="multipart/form-data" class="bar">
    <input type="hidden" name="op" value="upload_file">
    <input type="hidden" name="fid" value="<?= (int) $fid ?>">
    选择文件：<input type="file" name="upload_file" required>
    <button type="submit">上传</button>
</form>

<table>
    <thead>
    <tr><th>名称</th><th>大小</th><th>上传/创建时间</th><th>操作</th></tr>
    </thead>
    <tbody>
    <?php foreach ($folderRows as $row): ?>
        <tr>
            <td>[目录] <?= htmlspecialchars((string) $row['folder_name'], ENT_QUOTES, 'UTF-8') ?></td>
            <td>-</td>
            <td><?= htmlspecialchars((string) $row['folder_time'], ENT_QUOTES, 'UTF-8') ?></td>
            <td>
                <a href="?fid=<?= (int) $row['folder_id'] ?>">打开</a>
                <a href="?fid=<?= (int) $fid ?>&action=delete_folder&id=<?= (int) $row['folder_id'] ?>" onclick="return confirm('确认删除该文件夹?')">删除</a>
            </td>
        </tr>
    <?php endforeach; ?>

    <?php foreach ($fileRows as $row): ?>
        <tr>
            <td><?= htmlspecialchars((string) $row['file_name'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= (int) $row['file_size'] ?> B</td>
            <td><?= htmlspecialchars((string) $row['file_time'], ENT_QUOTES, 'UTF-8') ?></td>
            <td>
                <a href="?fid=<?= (int) $fid ?>&action=download&id=<?= (int) $row['file_id'] ?>">下载</a>
                <a href="?fid=<?= (int) $fid ?>&action=delete_file&id=<?= (int) $row['file_id'] ?>" onclick="return confirm('确认删除该文件?')">删除</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>
