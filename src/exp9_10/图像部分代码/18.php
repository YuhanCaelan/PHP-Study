<?php
	/**
		用于对图片进行缩放
		@param	string	$filename	图片的URL
		@width	int		$width		设置图片缩放的最大宽度
		@height	int		$height		设置图片缩放的最大高度
		ImageCopyResampled(dest,src,dx,dy,sx,sy,dw,dh,sw,sh):从原图像(source)中抓取特定位置（sx,sy）复制图像区域到目标图像(destination)的特定位置(dx,dy)
	*/
	function thumb($filename, $width, $height) {
		/* 获取原图像$filename的宽度$width_orig和高度$hteight_orig */
		list($width_orig, $height_orig) = getimagesize($filename);

		/* 根据参数$width和$height值，换算出等比例缩放的高度和宽度 */
		// 如果宽>长,则按宽缩放
		// 如果 宽<=长,则按长缩放
		if ($width && ($width_orig < $height_orig)) {
			$width = ($height / $height_orig) * $width_orig;
		} else {
			$height = ($width / $width_orig) * $height_orig;
		}
		
		   /*$width=$width_orig*0.5;//直接乘以缩放比例
           $height=$height_orig*0.5;*/
		 

		/* 将原图缩放到这个新创建的图片资源中 */
		$image_p = imagecreatetruecolor($width, $height);
		/* 获取原图的图像资源 */
		$image = imagecreatefromjpeg($filename);
		
		/*使用imagecopyresampled()函数进行缩放设置 */
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

		/* 将缩放后的图片$image_p保存， 100（最佳质量，文件最大) */
		$filename="img/resample.jpg";
		imagejpeg($image_p, $filename, 100);
		echo "<img src=$filename>";
		imagedestroy($image_p);     	//销毁图片资源$image_p
		imagedestroy($image);       	//销毁图片资源$image
		
	}
	
	thumb("img/孩子.jpg", 100,100);  		//将孩子1.jpg图片缩放成100x100的小图
	/* thumb("brophp.jpg", 200,2000);  		//如果按一边进行等比例缩放，只需要将另一边给个无限大的值 */
	
	
	
	

