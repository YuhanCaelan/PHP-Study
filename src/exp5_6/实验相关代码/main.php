<?php define('APP','itcast');?>
<!doctype html>
<html>
<head>
 <meta charset="utf-8">
 <title>网页布局</title>
 <link href="CSS/style.css" rel="stylesheet" /> 
</head>
<body>
	<div class="title">header</div>
	<div class="main">
		<div class="content"><?php include './content.php';?></div>
		<div class="side"><?php include './side.php';?></div>
	</div>
	<div class="footer">footer</div>
</body>
</html>
<?php 
header('Content-Type: text/html; charset=GBK');
/*第一行代码出于安全性考虑，在模板文件的顶端定义一个常量，并在其引入文件的顶部验证该常量
是否存在，从而限定用户只能访问模板文件，而不能单独访问被引入的文件。*/
?>