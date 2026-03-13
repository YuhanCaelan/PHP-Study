<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo '请先在表单页面提交学生信息。';
    exit;
}

$studentId = $_POST['student_id'] ?? '';
$name = $_POST['name'] ?? '';
$gender = $_POST['gender'] ?? '';
$birthDate = $_POST['birth_date'] ?? '';
$major = $_POST['major'] ?? '';
$courses = $_POST['courses'] ?? [];
$remark = $_POST['remark'] ?? '';
$hobbies = $_POST['hobbies'] ?? [];

$courseText = empty($courses) ? '未选择' : implode('、', $courses);
$hobbyText = empty($hobbies) ? '未选择' : implode('、', $hobbies);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>学生信息展示</title>
    <style>
        body {
            font-family: "Microsoft YaHei", sans-serif;
            background: #f7f7f7;
        }
        .result-wrap {
            width: 620px;
            margin: 30px auto;
            background: #ffffff;
            border: 1px solid #dcdcdc;
            padding: 14px;
        }
        h2 {
            margin: 0 0 12px;
            color: #2f5fa9;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #d9d9d9;
            padding: 9px 10px;
            text-align: left;
        }
        th {
            width: 120px;
            background: #f3f6fb;
        }
        .back {
            margin-top: 12px;
        }
    </style>
</head>
<body>
    <div class="result-wrap">
        <h2>学生信息展示</h2>
        <table>
            <tr>
                <th>学号</th>
                <td><?php echo htmlspecialchars($studentId); ?></td>
            </tr>
            <tr>
                <th>姓名</th>
                <td><?php echo htmlspecialchars($name); ?></td>
            </tr>
            <tr>
                <th>性别</th>
                <td><?php echo htmlspecialchars($gender); ?></td>
            </tr>
            <tr>
                <th>出生日期</th>
                <td><?php echo htmlspecialchars($birthDate); ?></td>
            </tr>
            <tr>
                <th>所学专业</th>
                <td><?php echo htmlspecialchars($major); ?></td>
            </tr>
            <tr>
                <th>所学课程</th>
                <td><?php echo htmlspecialchars($courseText); ?></td>
            </tr>
            <tr>
                <th>备注</th>
                <td><?php echo nl2br(htmlspecialchars($remark)); ?></td>
            </tr>
            <tr>
                <th>兴趣</th>
                <td><?php echo htmlspecialchars($hobbyText); ?></td>
            </tr>
        </table>
        <div class="back">
            <a href="example1-7.php">返回继续填写</a>
        </div>
    </div>
</body>
</html>