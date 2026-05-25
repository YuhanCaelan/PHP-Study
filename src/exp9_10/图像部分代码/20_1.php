<?php
	/**
		为背景图片添加文字水印（位置固定）,背景图片格式为jpeg
		@param	string	$filename	需要添加水印的背景图片
		@param	string	$text		水印文字
	*/ 
	function watermark($filename){								
		$back = imagecreatefromjpeg($filename);   			//创建背景图片的资源
		$font_style = 'C:\Windows\Fonts\STSONG.TTF';//华文宋体
		//设置字体颜色
		$color = imagecolorallocate($back, 0xff, 0x00, 0xff);
		$str="快乐学习PHP";
		$text=iconv("gb2312", "utf-8", $str);
	/**imagefttext ( resource $image , float $size , float $angle , int $x , int $y , int $color , string $fontfile , string $text , array $extrainfo = ? ) : array
	 * 将文本写入图像
	 */
		imagefttext($back, 30, 0, 0,35, $color, $font_style, $text);
		
		/* 保存带有水印的背景图片 */
		$file="sumt.jpg";
		imagejpeg($back,$file);
		imagedestroy($back);				//销毁背景图片资源$back
		echo "<img src=$file>";
	}
	
	watermark("./class.jpg");
	
	


