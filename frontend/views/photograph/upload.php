<?php
$upload_file=$_FILES['trackdata']['tmp_name'];  
$upload_file_name=$_FILES['trackdata']['name'];  
 
if($upload_file){  
	$upload_file_size = $_FILES["trackdata"]["size"];
	$file_size_max = 1000*1000;// 1M限制文件上传最大容量(bytes)  
	$store_dir = "d:/";// 上传文件的储存位置  
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
?> 