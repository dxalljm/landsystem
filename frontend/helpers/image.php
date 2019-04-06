<?php
$data=file_get_contents("../web/images/plant.jpg");
$mth = new mht
$im = base64_encode($data);
if ($im !== false) {
// 	header('Content-Type: image/jpeg'); //对应jpeg的类型
	base64_decode($im);////也要对应jpeg的类型
// 	imagedestroy($im);
}
else {
	echo '图片未读入';
}
?>