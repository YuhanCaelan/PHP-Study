<?php
declare(strict_types=1);

/**
 * 1) 获取文件后缀名
 */
function getFileExtension(string $filename): string
{
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    return $ext === '' ? '' : strtolower($ext);
}

/**
 * 2) 读取源文件部分内容写入目标文件
 */
function writePartialContent(string $sourcePath, string $targetPath, int $start, int $length): bool
{
    if (!is_file($sourcePath) || $length <= 0 || $start < 0) {
        return false;
    }

    $content = file_get_contents($sourcePath);
    if ($content === false) {
        return false;
    }

    $partial = mb_substr($content, $start, $length, 'UTF-8');
    return file_put_contents($targetPath, $partial) !== false;
}

/**
 * Linux 环境下优先尝试 /mnt/d，否则写入当前实验目录的 d_uploads
 */
function resolveDDrivePath(): string
{
    $candidates = [
        '/mnt/d',
        '/media/d',
        sys_get_temp_dir() . '/exp7_8_d_uploads',
    ];

    foreach ($candidates as $path) {
        if ((is_dir($path) || @mkdir($path, 0777, true)) && is_writable($path)) {
            return realpath($path) ?: $path;
        }
    }

    return __DIR__;
}

/**
 * 上传文件到 D 盘路径（或替代目录）
 */
function uploadToDDrive(array $file): array
{
    if (!isset($file['tmp_name'], $file['name']) || (int) ($file['error'] ?? 1) !== UPLOAD_ERR_OK) {
        return [false, '上传文件无效'];
    }

    $targetDir = resolveDDrivePath();
    $baseName = basename((string) $file['name']);
    $targetPath = $targetDir . '/' . date('Ymd_His') . '_' . $baseName;

    if (!move_uploaded_file((string) $file['tmp_name'], $targetPath)) {
        return [false, '上传失败：无法写入目标路径'];
    }

    return [true, $targetPath];
}

/**
 * 3) 遍历目录并返回基本信息
 */
function scanDirectoryInfo(string $dir): array
{
    if (!is_dir($dir)) {
        return [];
    }

    $items = scandir($dir);
    if ($items === false) {
        return [];
    }

    $list = [];
    foreach ($items as $name) {
        $full = rtrim($dir, '/') . '/' . $name;
        $list[] = [
            'name' => $name,
            'size' => is_file($full) ? filesize($full) : 0,
            'type' => is_dir($full) ? 'dir' : 'file',
            'mtime' => date('Y/m/d H:i:s', filemtime($full) ?: time()),
        ];
    }

    return $list;
}

$msg = '';
$error = '';

$defaultSource = __DIR__ . '/data/source.txt';
$defaultTarget = sys_get_temp_dir() . '/exp7_8_target.txt';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $op = (string) ($_POST['op'] ?? '');

    if ($op === 'ext') {
        $filename = trim((string) ($_POST['filename'] ?? ''));
        if ($filename === '') {
            $error = '请输入文件名';
        } else {
            $ext = getFileExtension($filename);
            $msg = '后缀名：' . ($ext === '' ? '（无后缀）' : $ext);
        }
    }

    if ($op === 'partial') {
        $start = (int) ($_POST['start'] ?? 0);
        $length = (int) ($_POST['length'] ?? 20);
        $ok = writePartialContent($defaultSource, $defaultTarget, $start, $length);
        if ($ok) {
            $msg = '已写入：' . $defaultTarget;
        } else {
            $error = '部分内容写入失败';
        }
    }

    if ($op === 'upload') {
        [$ok, $result] = uploadToDDrive($_FILES['upload_file'] ?? []);
        if ($ok) {
            $msg = '上传成功：' . $result;
        } else {
            $error = $result;
        }
    }
}

$dirInput = trim((string) ($_GET['dir'] ?? (__DIR__ . '/filemanager/root')));
$scanRows = scanDirectoryInfo($dirInput);
?>
<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>exp7_8 必做任务</title>
    <style>
        body { font-family: "Microsoft YaHei", sans-serif; margin: 20px; }
        .card { border: 1px solid #ccc; padding: 12px; margin-bottom: 16px; border-radius: 6px; }
        h2 { margin: 0 0 10px; }
        .msg { color: #0a6e2f; }
        .err { color: #c62828; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #bdbdbd; padding: 6px; font-size: 14px; }
        input[type='text'], input[type='number'] { height: 28px; }
        .line { margin-bottom: 8px; }
    </style>
</head>
<body>
    <h1>exp7_8 必做内容（1-3）</h1>

    <?php if ($msg !== ''): ?><div class="msg"><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
    <?php if ($error !== ''): ?><div class="err"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>

    <div class="card">
        <h2>1. 获取文件后缀名</h2>
        <form method="post">
            <input type="hidden" name="op" value="ext">
            <div class="line">文件名：<input type="text" name="filename" placeholder="例如：photo.jpg" style="width: 220px;"> <button type="submit">获取后缀</button></div>
        </form>
    </div>

    <div class="card">
        <h2>2. 读取一个文件内容并将部分写入另一个文件，再上传到D盘</h2>
        <form method="post">
            <input type="hidden" name="op" value="partial">
            <div class="line">源文件：<?= htmlspecialchars($defaultSource, ENT_QUOTES, 'UTF-8') ?></div>
            <div class="line">目标文件：<?= htmlspecialchars($defaultTarget, ENT_QUOTES, 'UTF-8') ?></div>
            <div class="line">起始字符：<input type="number" name="start" value="0"> 长度：<input type="number" name="length" value="40"> <button type="submit">执行部分写入</button></div>
        </form>
        <form method="post" enctype="multipart/form-data" style="margin-top: 10px;">
            <input type="hidden" name="op" value="upload">
            <div class="line">选择文件上传至 D 盘（Linux 下优先 `/mnt/d`，否则写入 `exp7_8/d_uploads`）：</div>
            <input type="file" name="upload_file" required>
            <button type="submit">上传</button>
        </form>
    </div>

    <div class="card">
        <h2>3. 遍历目录并显示子目录和文件基本信息</h2>
        <form method="get" class="line">
            目录：<input type="text" name="dir" value="<?= htmlspecialchars($dirInput, ENT_QUOTES, 'UTF-8') ?>" style="width: 460px;">
            <button type="submit">遍历</button>
        </form>
        <table>
            <thead><tr><th>文件名</th><th>文件大小</th><th>文件类型</th><th>修改时间</th></tr></thead>
            <tbody>
            <?php foreach ($scanRows as $r): ?>
                <tr>
                    <td><?= htmlspecialchars((string) $r['name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= (int) $r['size'] ?></td>
                    <td><?= htmlspecialchars((string) $r['type'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars((string) $r['mtime'], ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="card">
        <h2>选做入口</h2>
        <div><a href="filemanager/index.php">4. 文件管理器</a></div>
        <div><a href="upload/index.php">5. 在线网盘</a></div>
    </div>
</body>
</html>
