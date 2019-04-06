<?php
namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

/**
 * UploadForm is the model behind the upload form.
 */
class Photograph extends Model
{
	/**
	 * @var UploadedFile file attribute
	 */
	public $file;

	/**
	 * @return array the validation rules.
	 */
	public function rules()
	{
		return [
// 				[['file'], 'file'],
		];
	}

	public static function downfile($url,$filename,$path)
	{
		ob_start();
		header("Content-type:  application/octet-stream");
		header("Accept-Ranges:  bytes ");
		header( "Content-Disposition:  attachment;  filename= {$filename}.jpg");
		$size=readfile($url);
		header( "Accept-Length: " .$size);
		if(!file_exists($path)){
			mkdir($path, 0777);
		}
	}


	public static function httpcopy($url, $file="", $timeout=60) {
		$file = empty($file) ? pathinfo($url,PATHINFO_BASENAME) : $file;
		$dir = pathinfo($file,PATHINFO_DIRNAME);
		!is_dir($dir) && @mkdir($dir,0755,true);
		$url = str_replace(" ","%20",$url);

		if(function_exists('curl_init')) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$temp = curl_exec($ch);
			if(@file_put_contents($file, $temp) && !curl_error($ch)) {
				return $file;
			} else {
				return false;
			}
		} else {
			$opts = array(
				"http"=>array(
					"method"=>"GET",
					"header"=>"",
					"timeout"=>$timeout)
			);
			$context = stream_context_create($opts);
			if(@copy($url, $file, $context)) {
				//$http_response_header
				return $file;
			} else {
				return false;
			}
		}
	}

	/*
	*功能：php完美实现下载远程图片保存到本地
	*参数：文件url,保存文件目录,保存文件名称，使用的下载方式
	*当保存文件名称为空时则使用远程文件原来的名称
	*/
	public static function getImage($url,$save_dir='',$filename='',$type=0){
		if(trim($url)==''){
			return array('file_name'=>'','save_path'=>'','error'=>1);
		}
		if(trim($save_dir)==''){
			$save_dir='./';
		}
		if(trim($filename)==''){//保存文件名
			$ext=strrchr($url,'.');
			if($ext!='.gif'&&$ext!='.jpg'){
				return array('file_name'=>'','save_path'=>'','error'=>3);
			}
			$filename=time().$ext;
		}
		if(0!==strrpos($save_dir,'/')){
			$save_dir.='/';
		}
		//创建保存目录
		if(!file_exists($save_dir)&&!mkdir($save_dir,0777,true)){
			return array('file_name'=>'','save_path'=>'','error'=>5);
		}
		//获取远程文件所采用的方法
		if($type){
			$ch=curl_init();
			$timeout=5;
			curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
			$img=curl_exec($ch);
			curl_close($ch);
		}else{
			ob_start();
			readfile($url);
			$img=ob_get_contents();
			ob_end_clean();
		}
		//$size=strlen($img);
		//文件大小
		$fp2=@fopen($save_dir.$filename,'a');
		fwrite($fp2,$img);
		fclose($fp2);
		unset($img,$url);
		return array('file_name'=>$filename,'save_path'=>$save_dir.$filename,'error'=>0);
	}

	public static function batchDownload($fileArray)
	{
//		preg_match_all($reg,$content,$matches);
		$imgUrl = $fileArray['img'];
		var_dump($imgUrl);
		foreach ($imgUrl as $key => $value) {
			$urlHead = 'http://192.168.1.10/';
			switch ($key) {
				case 'photo':
					$url = $urlHead.$value;
					$filename = '法人近照';
					break;
				case 'cardpic':
					$url = $urlHead.$value;
					$filename = '身份证正面';
					break;
				case 'cardpicback':
					$url = $urlHead.$value;
					$filename = '身份证反面';
					break;
			}
			self::downfile($url,$filename,$fileArray['path']);
		}
//		for($i = 0;$i < count($fileArray['img']);$i ++){
//
//			/*explode
//            $url_arr[$i] = explode('/', $matches[1][$i]);
//            $last = count($url_arr[$i])-1;
//            */
//
//			//strrchr
//			$filename = strrchr($fileArray['img'][$i], '/');
//
//			downImage($fileArray['img'][$i],$path.$filename);
//			//downImage($matches[1][$i],$path.'/'.$url_arr[$i][$last]);
//		}
		echo '下载完毕';
	}
}