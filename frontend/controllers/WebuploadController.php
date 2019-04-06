<?php

namespace frontend\controllers;

use app\models\Fireimg;
use Yii;
use yii\web\Controller;
use frontend\helpers\fileUtil;
use app\models\Tablefields;
use app\models\User;
use app\models\Farms;
use app\models\ManagementArea;
use app\models\Picturelibrary;
/**
 * AfterchenqianController implements the CRUD actions for Afterchenqian model.
 */
class WebuploadController extends Controller
{
    public function actionShow()
    {
        return $this->renderAjax('show');
    }

    public function actionFtpupload($farms_id=null,$class=null,$field)
    {
        $farm = Farms::findOne($farms_id);
        $ftp = Yii::$app->ftp;
        $fileList = fileUtil::ListDir('vendor/bower/webuploader-master/server/upload');
//        var_dump($fileList);exit;
        $strc = iconv("UTF-8", "gbk//TRANSLIT", $farm['farmname'] . '-' . $farm['farmername']);
        $stra = iconv("UTF-8", "gbk//TRANSLIT", Tablefields::getCTable($class));
        $cname = iconv("UTF-8", "gbk//TRANSLIT", Tablefields::getCfields($class, $field));
        $management_area = iconv("UTF-8", "gbk//TRANSLIT", ManagementArea::getAreanameOne($farm['management_area']));
        foreach ($fileList as $key => $file) {
            $imageInfo = $this->getImageInfo('vendor/bower/webuploader-master/server/upload/' . $file);

//            $newFilename = time();
            $newFilename = $cname . '_0';
//            var_dump($newFilename);exit;
            $filename = $newFilename . '.' . $imageInfo['type'];
//            $path = $management_area.'/'.$strc.'/'.$stra;
            $farmerpath = 'photo_gallery/'.$management_area . '/' . $strc . '/' . $stra.'/'.$cname;
            //			var_dump($path);
            $filepath = 'vendor/bower/webuploader-master/server/upload/' . $file;
            $farmerfilepath = $farmerpath . '/' . $filename;
            $uploadpath = $management_area . '/' . $strc . '/' . $stra.'/'.$cname;

            $ftp->chdir('/');
            $gen = $this->dirArray($ftp->ls());
//            var_dump($gen);exit;
            foreach (explode('/', $uploadpath) as $value) {
//                var_dump($value);
                switch ($value) {
                    case $management_area:
                        if (in_array($value, $gen)) {
                            $gen = $this->dirArray($ftp->ls($value));
                            $ftp->chdir($value);
                        } else {
                            $ftp->mkdir($value);
                            $ftp->chdir($value);
                        }
                        break;
                    case $strc:
                        if (in_array($value, $gen)) {
                            $gen = $this->dirArray($ftp->ls($value));
                            $ftp->chdir($value);
                        } else {
                            $ftp->mkdir($value);
                            $ftp->chdir($value);
                        }
                        break;
                    case $stra:
                        if (in_array($value, $gen)) {
                            $gen = $this->dirArray($ftp->ls($value));
                            $ftp->chdir($value);
                        } else {
                            $ftp->mkdir($value);
                            $ftp->chdir($value);
                        }
                        break;
                    case $cname:
                        if (in_array($value, $gen)) {
                            $gen = $this->dirArray($ftp->ls($value));
                            $ftp->chdir($value);
                        } else {
                            $ftp->mkdir($value);
                            $ftp->chdir($value);
                        }
                        break;
                }
            }
            $pics = Picturelibrary::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear(),'classname'=>$class,'field'=>$field])->count();
//            var_dump($pics);
            if($pics) {
                $newFilename = $cname . '_' .$pics++;
                $filename = $newFilename . '.' . $imageInfo['type'];
                $farmerfilepath = $farmerpath . '/' . $filename;
            }
//            var_dump($newFilename);exit;
            if ($ftp->put($filepath,$filename)) {
                fileUtil::unlinkFile($filepath);
                $model = new Picturelibrary();
                $model->farms_id = $farms_id;
                $model->create_at = time();
                $model->year = User::getYear();
                $model->classname = $class;
                $model->field = $field;
                $model->pic = iconv("gbk//TRANSLIT", "UTF-8", $farmerfilepath);
                $model->save();
                $id = $farm['id'];
                echo json_encode(['url' => 'http://192.168.1.10/' . iconv("GBK", "UTF-8", $farmerfilepath), 'info' => $imageInfo, 'id' => $id]);
            }
        }
//            exit;
    }

    public function dirArray($array)
    {
        $result = [];
        foreach($array as $value) {
//			print_r($value->filename);
            $result[] = $value->filename;
        }
        return $result;
    }

    public function getImageInfo($images)
    {
        $img_info = getimagesize($images);
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
        $img_size = ceil(filesize($images)/1000)."k";
        $new_img_info = array (
                'file' => $images,
                "width"=>$img_info[0], //图像宽
                "height"=>$img_info[1], //图像高
                "type"=>$img_type, //图像类型
                "size"=>$img_size, //图像大小
//                "extension"=>$img_info[2],//扩展名
        );
        return $new_img_info;
    }

    public function actionShowimg($farms_id,$class=NULL)
    {
        $farm = Farms::findOne($farms_id);
        $ftp = Yii::$app->ftp;
        $strc = iconv("UTF-8", "gbk//TRANSLIT", $farm['farmname'] . '-' . $farm['farmername']);
        $stra = iconv("UTF-8", "gbk//TRANSLIT", Tablefields::getCTable($class));
//        $cname = iconv("UTF-8", "gbk//TRANSLIT", Tablefields::getCfields($class, $field));
        $management_area = iconv("UTF-8", "gbk//TRANSLIT", ManagementArea::getAreanameOne($farm['management_area']));
        $path = $management_area.'/'.$strc;
        if(empty($class)) {
            $pics = Picturelibrary::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->all();
        } else {
            $pics = Picturelibrary::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear(),'classname'=>$class])->all();
        }

        $ftp->chdir('/');
        $gen = $this->dirArray($ftp->ls());
//            var_dump($gen);exit;
        foreach (explode('/', $path) as $value) {
//                var_dump($value);
            switch ($value) {
                case $management_area:
                    if (in_array($value, $gen)) {
                        $gen = $this->dirArray($ftp->ls($value));
                        $ftp->chdir($value);
                    } else {
                        $ftp->mkdir($value);
                        $ftp->chdir($value);
                    }
                    break;
                case $strc:
                    if (in_array($value, $gen)) {
                        $gen = $this->dirArray($ftp->ls($value));
                        $ftp->chdir($value);
                    } else {
                        $ftp->mkdir($value);
                        $ftp->chdir($value);
                    }
                    break;
                case $stra:
//                        var_dump($value);
//                        var_dump($gen);
                    if (in_array($value, $gen)) {
//                            var_dump('yes');
                        $gen = $this->dirArray($ftp->ls($value));
//                            var_dump($gen);
                        $ftp->chdir($value);
                    } else {
                        $ftp->mkdir($value);
                        $ftp->chdir($value);
                    }
                    break;
            }
        }
        return $this->renderAjax('showimg',[
            'farms_id' => $farms_id,
            'class' => $class,
            'pics' => $pics,
        ]);
    }
}
