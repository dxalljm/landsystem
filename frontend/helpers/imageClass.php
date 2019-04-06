<?php
namespace frontend\helpers;
class imageClass extends baseArraySearch
{
	
	public static function getImageInfo($images)
	{
//		var_dump($images);
//		$imagescode = urlencode($images);
//		var_dump($images);exit;
		if($images == '')
			return '';
		$img_info = getimagesize('http://192.168.1.10/'.$images);
//		var_dump($img_info);exit;
		switch ($img_info[2]){
			case 1:
				$imgtype = "gif";
				break;
			case 2:
				$imgtype = "jpg";
				break;
			case 3:
				$imgtype = "png";
				break;
		}
		$img_type = $imgtype;
		//获取文件大小
//		$img_size = ceil(filesize('http://192.168.1.10/'.$images)/1000)."k";
		$new_img_info = array (
				'file' => 'http://192.168.1.10/'.$images,
				"width"=>$img_info[0], //图像宽
				"height"=>$img_info[1], //图像高
				"type"=>$img_type, //图像类型
//				"size"=>$img_size //图像大小
		);
// 		var_dump($new_img_info);exit;
		return $new_img_info;
	}

}