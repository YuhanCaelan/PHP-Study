<?php
$now = new DateTime("now", new DateTimeZone("Asia/Shanghai"));
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>PHP Demo</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 40px; }
    .card { padding: 16px; border: 1px solid #ddd; border-radius: 8px; max-width: 520px; }
    code { background: #f6f6f6; padding: 2px 6px; border-radius: 4px; }
  </style>
</head>
<body>
  <div class="card">
    <h1>PHP ok</h1>
    <p>Server time: <code><?php echo $now->format("Y-m-d H:i:s"); ?></code></p>
    <p>Edit <code>src/index.php</code> to see live reload.</p>
  </div>
</body>
</html>
