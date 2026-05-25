<?php
declare(strict_types=1);

$user = ['id' => 234, 'name' => '张三'];
$uploadDir = __DIR__ . '/uploads';
$message = '';
$error = '';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}
chmod($uploadDir, 0777);

function findAvatar(string $uploadDir, int $userId): string
{
    foreach (['jpg', 'png', 'gif'] as $ext) {
        $path = $uploadDir . '/' . $userId . '.' . $ext;
        if (is_file($path)) {
            return 'uploads/' . $userId . '.' . $ext . '?v=' . filemtime($path);
        }
    }

    return '图像部分代码/img/default.jpg';
}

function saveAvatar(array $file, string $uploadDir, int $userId): array
{
    if (!isset($file['error']) || (int) $file['error'] === UPLOAD_ERR_NO_FILE) {
        return [false, '请选择要上传的头像。'];
    }

    if ((int) $file['error'] !== UPLOAD_ERR_OK) {
        return [false, '上传失败，错误代码：' . (int) $file['error']];
    }

    $imageInfo = getimagesize((string) $file['tmp_name']);
    if ($imageInfo === false) {
        return [false, '上传的文件不是有效图片。'];
    }

    $extensionMap = [
        IMAGETYPE_JPEG => 'jpg',
        IMAGETYPE_PNG => 'png',
        IMAGETYPE_GIF => 'gif',
    ];
    $imageType = (int) $imageInfo[2];
    if (!isset($extensionMap[$imageType])) {
        return [false, '头像只支持 jpg、png、gif 图片。'];
    }

    $extension = $extensionMap[$imageType];
    foreach (['jpg', 'png', 'gif'] as $ext) {
        $oldFile = $uploadDir . '/' . $userId . '.' . $ext;
        if ($ext !== $extension && is_file($oldFile)) {
            unlink($oldFile);
        }
    }

    $targetPath = $uploadDir . '/' . $userId . '.' . $extension;
    return move_uploaded_file((string) $file['tmp_name'], $targetPath)
        ? [true, '头像保存成功，页面已按头像尺寸缩放显示。']
        : [false, '头像保存失败，请检查 uploads 目录权限。'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    [$ok, $result] = saveAvatar($_FILES['avatar'] ?? [], $uploadDir, $user['id']);
    if ($ok) {
        $message = $result;
    } else {
        $error = $result;
    }
}

$avatar = findAvatar($uploadDir, $user['id']);
?>
<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>用户头像上传</title>
    <style>
        body { margin: 0; font-family: "Microsoft YaHei", Arial, sans-serif; background: #eef1f4; color: #222; }
        .box { width: 380px; margin: 70px auto; padding: 24px 28px; background: #fff; border: 1px solid #d4dae0; border-radius: 8px; }
        h1 { margin: 0 0 18px; font-size: 22px; text-align: center; }
        .profile { display: flex; align-items: center; gap: 16px; margin-bottom: 18px; }
        .avatar { width: 120px; height: 120px; object-fit: contain; border: 1px solid #aab4bf; background: #f8fafc; }
        input[type="file"] { width: 100%; margin: 12px 0; }
        button { width: 100px; height: 34px; border: 1px solid #1683d8; border-radius: 4px; background: #1683d8; color: #fff; font-weight: 700; cursor: pointer; }
        .message, .error { margin-bottom: 12px; padding: 8px 10px; border-radius: 4px; font-size: 14px; }
        .message { background: #e9f8ef; color: #146c2e; }
        .error { background: #fdecea; color: #b42318; }
    </style>
</head>
<body>
    <main class="box">
        <h1>编辑用户头像</h1>
        <?php if ($message !== ''): ?><div class="message"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
        <?php if ($error !== ''): ?><div class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
        <div class="profile">
            <img class="avatar" src="<?= htmlspecialchars($avatar, ENT_QUOTES, 'UTF-8') ?>" alt="当前头像">
            <div>
                <p>用户名：<?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?></p>
                <p>当前头像</p>
            </div>
        </div>
        <form method="post" enctype="multipart/form-data">
            <label for="avatar">上传头像</label>
            <input id="avatar" name="avatar" type="file" accept="image/jpeg,image/png,image/gif" required>
            <button type="submit">保存头像</button>
        </form>
    </main>
</body>
</html>
