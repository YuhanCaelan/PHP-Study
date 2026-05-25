<?php
declare(strict_types=1);

$seedDir = realpath(__DIR__ . '/root');
$workRoot = sys_get_temp_dir() . '/exp7_8_filemanager_root';
if (!is_dir($workRoot) && !mkdir($workRoot, 0777, true)) {
    die('无法创建可写工作目录');
}

if ($seedDir !== false) {
    $seedItems = scandir($seedDir);
    if ($seedItems !== false) {
        foreach ($seedItems as $seedName) {
            if ($seedName === '.' || $seedName === '..') {
                continue;
            }
            $from = $seedDir . '/' . $seedName;
            $to = $workRoot . '/' . $seedName;
            if (is_file($from) && !file_exists($to)) {
                @copy($from, $to);
            }
            if (is_dir($from) && !is_dir($to)) {
                @mkdir($to, 0777, true);
            }
        }
    }
}

$rootDir = realpath($workRoot);
if ($rootDir === false) {
    die('工作目录无效');
}

function safeRealPath(string $rootDir, string $relative): string
{
    $relative = trim($relative);
    $relative = str_replace('\\', '/', $relative);
    $relative = ltrim($relative, '/');
    $full = $rootDir . '/' . $relative;
    $real = realpath($full);
    if ($real === false || strpos($real, $rootDir) !== 0) {
        return $rootDir;
    }

    return $real;
}

function relPath(string $rootDir, string $full): string
{
    $p = str_replace($rootDir, '', $full);
    return ltrim(str_replace('\\', '/', $p), '/');
}

$currentRel = (string) ($_GET['path'] ?? '');
$currentDir = safeRealPath($rootDir, $currentRel);
$currentRel = relPath($rootDir, $currentDir);

$msg = '';
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $op = (string) ($_POST['op'] ?? '');
    $path = (string) ($_POST['path'] ?? $currentRel);
    $dir = safeRealPath($rootDir, $path);

    if ($op === 'rename') {
        $name = basename((string) ($_POST['name'] ?? ''));
        $newName = basename((string) ($_POST['new_name'] ?? ''));
        if ($name === '' || $newName === '') {
            $err = '重命名参数不完整';
        } else {
            $from = $dir . '/' . $name;
            $to = $dir . '/' . $newName;
            if (!file_exists($from)) {
                $err = '源文件不存在';
            } elseif (file_exists($to)) {
                $err = '目标名称已存在';
            } elseif (!rename($from, $to)) {
                $err = '重命名失败';
            } else {
                $msg = '重命名成功';
            }
        }
    }

    if ($op === 'copy') {
        $name = basename((string) ($_POST['name'] ?? ''));
        $copyName = basename((string) ($_POST['copy_name'] ?? ''));
        if ($name === '' || $copyName === '') {
            $err = '复制参数不完整';
        } else {
            $from = $dir . '/' . $name;
            $to = $dir . '/' . $copyName;
            if (!is_file($from)) {
                $err = '仅支持复制文件';
            } elseif (file_exists($to)) {
                $err = '目标文件已存在';
            } elseif (!copy($from, $to)) {
                $err = '复制失败';
            } else {
                $msg = '复制成功';
            }
        }
    }

    if ($op === 'delete') {
        $name = basename((string) ($_POST['name'] ?? ''));
        if ($name === '') {
            $err = '删除参数不完整';
        } else {
            $target = $dir . '/' . $name;
            if (is_file($target)) {
                if (!unlink($target)) {
                    $err = '删除文件失败';
                } else {
                    $msg = '删除文件成功';
                }
            } elseif (is_dir($target)) {
                $items = scandir($target);
                if ($items !== false && count($items) > 2) {
                    $err = '目录非空，暂不支持直接删除';
                } elseif (!rmdir($target)) {
                    $err = '删除目录失败';
                } else {
                    $msg = '删除目录成功';
                }
            } else {
                $err = '目标不存在';
            }
        }
    }

    $currentDir = $dir;
    $currentRel = relPath($rootDir, $currentDir);
}

$items = scandir($currentDir);
if ($items === false) {
    $items = [];
}

$parentRel = '';
if ($currentDir !== $rootDir) {
    $parentRel = relPath($rootDir, dirname($currentDir));
}
?>
<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>文件管理器</title>
    <style>
        body { font-family: "Microsoft YaHei", sans-serif; margin: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; font-size: 14px; }
        th { background: #f2f2f2; }
        .msg { color: #0a6e2f; }
        .err { color: #c62828; }
        .op a { margin-right: 6px; }
        .toolbar { margin: 10px 0; }
    </style>
</head>
<body>
<h2>文件管理器</h2>
<div>当前位置：<?= htmlspecialchars('/' . $currentRel, ENT_QUOTES, 'UTF-8') ?></div>
<div><a href="../exp7_8_tasks.php">返回任务页</a></div>
<?php if ($msg !== ''): ?><div class="msg"><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
<?php if ($err !== ''): ?><div class="err"><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>

<div class="toolbar">
    <?php if ($currentDir !== $rootDir): ?>
        <a href="?path=<?= urlencode($parentRel) ?>">返回上一级目录</a>
    <?php endif; ?>
</div>

<table>
    <thead>
    <tr><th>名称</th><th>修改日期</th><th>大小</th><th>类型</th><th>操作</th></tr>
    </thead>
    <tbody>
    <?php foreach ($items as $name): ?>
        <?php
            if ($name === '.') { continue; }
            $full = $currentDir . '/' . $name;
            $isDir = is_dir($full);
            $type = $isDir ? 'dir' : 'file';
            $size = $isDir ? '-' : ((string) filesize($full));
            $mtime = date('Y/m/d H:i:s', filemtime($full) ?: time());
            $childRel = relPath($rootDir, $full);
        ?>
        <tr>
            <td><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($mtime, ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($size, ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($type, ENT_QUOTES, 'UTF-8') ?></td>
            <td class="op">
                <?php if ($isDir): ?>
                    <a href="?path=<?= urlencode($childRel) ?>">打开</a>
                <?php endif; ?>

                <form method="post" style="display:inline;">
                    <input type="hidden" name="op" value="rename">
                    <input type="hidden" name="path" value="<?= htmlspecialchars($currentRel, ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" name="name" value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>">
                    <input type="text" name="new_name" placeholder="新名称" style="width:90px;">
                    <button type="submit">重命名</button>
                </form>

                <?php if (!$isDir): ?>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="op" value="copy">
                        <input type="hidden" name="path" value="<?= htmlspecialchars($currentRel, ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="name" value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>">
                        <input type="text" name="copy_name" placeholder="副本名" style="width:90px;">
                        <button type="submit">复制</button>
                    </form>
                <?php endif; ?>

                <form method="post" style="display:inline;" onsubmit="return confirm('确认删除 <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?> ?');">
                    <input type="hidden" name="op" value="delete">
                    <input type="hidden" name="path" value="<?= htmlspecialchars($currentRel, ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" name="name" value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>">
                    <button type="submit">删除</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>
