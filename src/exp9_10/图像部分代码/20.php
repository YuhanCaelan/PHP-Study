<?php
	/**
		为背景图片添加图片水印（位置随机）,背景图片格式为png, 水印图片格式为png
		@param	string	$filename	需要添加水印的背景图片
		@param	string	$water		水印图片
	*/
	function watermark($filename, $water){
		/* 获取背景图片的宽度和高度 */
		list($b_w, $b_h) = getimagesize($filename);
		
		/* 获取水印图片的宽度和高度 */
		list($w_w, $w_h) = getimagesize($water);
		
		/* 在背景图片中放水印图片的随机起始位置 */
		$posX = rand(0, ($b_w - $w_w)); 
		$posY = rand(0, ($b_h - $w_h)); 

		$back = imagecreatefrompng($filename);   			//创建背景图片的资源
		$water = imagecreatefrompng($water);               //创建水印图片的资源
		
		/** 使用imagecopy()函数将水印图片复制到背景图片指定的位置中
		 * imagecopy ( resource $dst_im , resource $src_im , int $dst_x , int $dst_y , int $src_x , int $src_y , int $src_w , int $src_h ) : bool
		 * 拷贝图像的一部分 */
		//imagecopy($back, $water, $posX, $posY, 0, 0, $w_w, $w_h);
		
		/**生成水印方式二：使用imagecopymerge()函数设置半透明水印
		 * imagecopymerge ( resource $dst_im , resource $src_im , int $dst_x , int $dst_y , int $src_x , int $src_y , int $src_w , int $src_h , int $pct ) : bool
		 *  拷贝并合并图像的一部分。$pct[0,100]:0%完全透明，100%不透明
		 */
		imagecopymerge ($back, $water, $posX, $posY, 0, 0, $w_w, $w_h, 30);
		
		/* 保存带有水印图片的背景图片 */
		//header("Content-type:image/jpeg");
		$file="img/sum.jpg";
		imagejpeg($back,$file);

		imagedestroy($back);				//销毁背景图片资源$back
		imagedestroy($water);               //销毁水印图片资源$water
		echo "<img src=$file>";
	}
	
	/* 调用watermark()函数，为背景JPEG格式的图片good.png，添加png格式的水印图片JDLogo.png */
	watermark("img/good.png", "img/JDLogo.png");
	
	

