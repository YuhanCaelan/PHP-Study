<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="UTF-8">
	<title>学生个人信息录入</title>
	<style>
		body {
			font-family: "Microsoft YaHei", sans-serif;
			background: #f7f7f7;
		}
		.form-wrap {
			width: 560px;
			margin: 24px auto;
			background: #b8e7b7;
			border: 1px solid #9ec29d;
			padding: 10px 12px;
			box-sizing: border-box;
		}
		.title {
			text-align: center;
			background: #d8d8d8;
			border: 1px solid #b5b5b5;
			margin-bottom: 10px;
			font-weight: bold;
		}
		table {
			width: 100%;
			border-collapse: collapse;
		}
		td {
			padding: 6px 2px;
			vertical-align: top;
		}
		.label {
			width: 110px;
		}
		input[type="text"],
		input[type="date"],
		select,
		textarea {
			width: 140px;
			box-sizing: border-box;
		}
		textarea {
			resize: none;
		}
		.actions {
			padding-top: 8px;
		}
	</style>
</head>
<body>
	<div class="form-wrap">
		<div class="title">学生个人信息</div>
		<form action="example1-7-result.php" method="post">
			<table>
				<tr>
					<td class="label">学号：</td>
					<td><input type="text" name="student_id" required></td>
				</tr>
				<tr>
					<td class="label">姓名：</td>
					<td><input type="text" name="name" required></td>
				</tr>
				<tr>
					<td class="label">性别：</td>
					<td>
						<label><input type="radio" name="gender" value="男" checked>男</label>
						<label><input type="radio" name="gender" value="女">女</label>
					</td>
				</tr>
				<tr>
					<td class="label">出生日期：</td>
					<td><input type="date" name="birth_date" required></td>
				</tr>
				<tr>
					<td class="label">所学专业：</td>
					<td>
						<select name="major" required>
							<option value="">请选择</option>
							<option value="计算机">计算机</option>
							<option value="软件工程">软件工程</option>
							<option value="网络工程">网络工程</option>
							<option value="信息管理">信息管理</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class="label">所学课程：</td>
					<td>
						<select name="courses[]" multiple size="3">
							<option value="计算机导论">计算机导论</option>
							<option value="数据结构">数据结构</option>
							<option value="数据库原理">数据库原理</option>
							<option value="操作系统">操作系统</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class="label">备注：</td>
					<td><textarea name="remark" rows="4" cols="18"></textarea></td>
				</tr>
				<tr>
					<td class="label">兴趣：</td>
					<td>
						<label><input type="checkbox" name="hobbies[]" value="听音乐">听音乐</label>
						<label><input type="checkbox" name="hobbies[]" value="看小说">看小说</label>
						<label><input type="checkbox" name="hobbies[]" value="上网">上网</label>
					</td>
				</tr>
				<tr>
					<td class="actions" colspan="2">
						<input type="submit" value="提交">
						<input type="reset" value="重置">
					</td>
				</tr>
			</table>
		</form>
	</div>
</body>
</html>
