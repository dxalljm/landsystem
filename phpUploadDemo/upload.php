<?php
var_dump($_FILES);
$upload_file=$_FILES['trackdata']['tmp_name'];  
$upload_file_name=$_FILES['trackdata']['name'];  

if($upload_file){  
	$upload_file_size = $_FILES["trackdata"]["size"];
	$file_size_max = 1000*1000;// 1M限制文件上传最大容量(bytes)  
	$store_dir = "d:\\tempImage";// 上传文件的储存位置  
	$accept_overwrite = 1;//是否允许覆盖相同文件  
	// 检查文件大小  
	if ($upload_file_size > $file_size_max) {  
		echo "对不起，你的文件容量大于规定";  
		exit;  
	}  
	 
	// 检查读写文件  
	if (file_exists($store_dir . $upload_file_name) && !$accept_overwrite) {  
		Echo "存在相同文件名的文件";  
		exit;  
	}  
	 
	//复制文件到指定目录  
	if (!move_uploaded_file($upload_file,$store_dir.$upload_file_name)) {  
		echo "复制文件失败";  
		exit;  
	}  
} 
// function getImageInfo($images)
// {
// 	$img_info = getimagesize($images);
// 	switch ($img_info[2]){
// 		case 1:
// 			$imgtype = "gif";
// 			break;
// 		case 2:
// 			$imgtype = "jpg";
// 			break;
// 		case 3:
// 			$imgtype = "png";
// 			break;
// 	}
// 	$img_type = $imgtype;
// 	//获取文件大小
// 	$img_size = ceil(filesize($images)/1000)."k";
// 	$new_img_info = array (
// 			'file' => $images,
// 			"width"=>$img_info[0], //图像宽
// 			"height"=>$img_info[1], //图像高
// 			"type"=>$img_type, //图像类型
// 			"size"=>$img_size //图像大小
// 	);
// 	return $new_img_info;
// }
// // $imageInfo = getImageInfo($upload_file);
// $file = UploadedFile::loadFiles($upload_file);

// $path = 'd:/jpg.jpg';

// $filepath = $path;   
// $file->saveAs2($filepath);
?> 