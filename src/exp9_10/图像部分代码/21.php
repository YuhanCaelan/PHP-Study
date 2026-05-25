<?php
	/**
		用给定角度旋转图像，以jpeg图处格式为例
		@param	string	$filename	要旋转的图片名称
		@param	int		$degrees	指定旋转的角度
	*/
	function rotate($filename, $degrees) {
		/* 创建图像资源，以jpeg格式为例 */
		$source = imagecreatefromjpeg($filename);
		/** 使用imagerotate()函数按指定的角度旋转 
		 * imagerotate ( resource $image , float $angle , int $bgd_color , int $ignore_transparent = 0 ) : resource*/
		$rotate = imagerotate($source, $degrees, 0);
		/* 将旋转后的图片保存 */
		imagejpeg($rotate, $filename); 
	}
	
	/* 将把一幅图像彩蛋1.jpg旋转 180 度,即上下颠倒 */
	rotate("./img/彩蛋.jpg", 180);
	
	

