<?php
function thumb($filename, $width, $height) {
//获取原图图像大小
list($width_orig, $height_orig) = getimagesize($filename);
//设置缩略图的最大宽度和高度
$maxwidth =$width;
$maxheight=$height;
//自动计算缩略图的宽和高:如果宽>长,则按宽缩放;如果 宽<=长,则按长缩放
if($width_orig > $height_orig){   		                         
    //缩略图的宽等于$maxwidth
    $newwidth = $maxwidth;
    //计算缩略图的高度
    $newheight = round($newwidth*$height_orig/$width_orig);
}
else{
    //缩略图的高等于$maxwidth
    $newheight = $maxheight;
    //计算缩略图的宽度 round()对浮点数进行四舍五入
    $newwidth = round($newheight*$width_orig/$height_orig);
}
//绘制缩略图的画布
$thumb = imageCreateTrueColor($newwidth,$newheight);
//依据原图创建一个与原图一样的新的图像
$source = imagecreatefromjpeg($filename);

//依据原图创建缩略图
/**
 * imagecopyresized(resource $dst_image , resource $src_image , int $dst_x , int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h ) : bool
 * 拷贝部分图像并调整大小
 *@param $thumb 目标图像
 *@param $source 原图像
 *@param 0,0,0,0 分别代表目标点的x坐标和y坐标，源点的x坐标和y坐标
 *@param $newwidth 目标图像的宽
 *@param $newheight 目标图像的高
 *@param $width 原图像的宽
 *@param $height 原图像的高
 */
 
imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width_orig, $height_orig);
//保存缩略图到指定目录
imagejpeg($thumb,$filename,100);
}
thumb("img/孩子.jpg", 100,100);