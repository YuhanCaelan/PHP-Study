<!-- 提交表单，使用 POST 方法发送数据到当前页面 -->
<form method="post" action="example1.php">
    <!-- 输入提示文本 -->
    <label>请输入一个不等于 0 的整数：</label>
    <!-- 输入框：name 为 b，提交后用于接收用户输入；value 用于回显上次输入 -->
    <input type="text" name="b" value="<?php echo isset($_POST['b']) ? htmlspecialchars($_POST['b']) : ''; ?>">
    <!-- 提交按钮 -->
    <input type="submit" value="提交">
</form>

<?php
// 判断是否为表单提交请求（仅在 POST 时处理数据）
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 获取用户输入：若不存在则默认空字符串，并去除首尾空格
    $input = trim($_POST['b'] ?? '');

    // 使用正则判断输入是否为整数（允许负号）
    if (!preg_match('/^-?\d+$/', $input)) {
        // 不是整数时输出错误提示
        echo '输入错误：请输入整数。';
    } else {
        // 将合法整数文本转换为整型
        $b = (int)$input;

        // 判断是否等于 0（题目要求不等于 0）
        if ($b === 0) {
            // 输入为 0 时输出错误提示
            echo '输入错误：请输入不等于 0 的整数。';
        } elseif ($b < 0) {
            // 负整数阶乘在本题中不处理，给出提示
            echo '负整数没有定义阶乘。';
        } else {
            // 初始化阶乘结果为 1
            $factorial = 1;
            // 从 1 循环乘到 b，计算 b 的阶乘
            for ($i = 1; $i <= $b; $i++) {
                // 每一步累计乘积
                $factorial *= $i;
            }
            // 输出最终结果
            echo $b . ' 的阶乘为：' . $factorial;
        }
    }
}
