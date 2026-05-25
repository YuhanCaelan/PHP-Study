<?php
//画图练习  
/*$im=imagecreatetruecolor(300, 300);
//$im=imagecreate($width, $height);
$red=imagecolorallocate($im, 255, 0, 0);
imagefill($im, 0, 0, $red);
header("Content-type:image/png");
imagepng($im);
imagedestroy($im);*/


//步1：创建画布，默认背景是黑色,可以改变颜色
$image=imagecreatetruecolor(400, 300);
$yellow=imagecolorallocate($image, 255, 255, 0);
imagefill($image, 0, 0, $yellow);
	//以图片作为画布背景
	//$srcim=imagecreatefromjpeg("彩蛋.jpg");
	//imagecopy($image, $srcim, 0, 0, 0, 0, 450, 325);


//步2：绘制需要的各种图形（圆、直线、矩形、弧线、扇形等）
//创建一个颜色
$color=imagecolorallocate($image, 255, 0, 0);

//画圆、椭圆
imageellipse($image, 50, 50, 30, 30, $color);
imageellipse($image, 100, 100, 50, 80, $color);
//画一个填充圆
imagefilledellipse($image, 80, 100, 30, 30, $color);

//画矩形
imagerectangle($image, 10, 10, 50, 50, $color);
//画一个填充矩形
imagefilledrectangle($image, 20, 80, 30, 90, $color);

//画直线
imageline($image, 100, 150, 200, 250, $color);	
//画对角线
imageline($image, 0, 0, 400, 300, $color);
//画弧线
imagearc($image, 200, 200, 80, 80, 0, 180, $color);
//画扇形：即填充的圆弧 
imagefilledarc($image, 180, 180, 50, 50, 0, 90, $color, IMG_ARC_PIE);
//画一个点
imagesetpixel($image, 280, 290, $color);
//写字：英文、中文
imagestring($image, 5, 100, 100, "hello", $color);
$str="我爱祖国";
$text=iconv("gb2312", "utf-8", $str);
$font="C:/Windows/Fonts/simfang.ttf";//用字体文件原路径有效，将字体文件复制到当前路径下无效
imagettftext($image, 35, 0, 50, 50, $color, $font, $text);

//步3：输出图像到网页或者保存
header("Content-type:image/png");
imagepng($image);
//$file="sum.gif";								
//imagegif($image,$file);//保存图片
//echo "<img src=$file>";	

//步4：销毁该图片，释放内存：服务器端的
imagedestroy($image);

