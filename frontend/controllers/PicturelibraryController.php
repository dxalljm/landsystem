<?php

namespace frontend\controllers;

use app\models\Farmerinfo;
use app\models\Fireimg;
use app\models\Fireprevention;
use app\models\Logs;
use app\models\ManagementArea;
use app\models\Picturelibrary;
use app\models\Farms;
use frontend\models\logSearch;
use Yii;
use yii\web\Controller;
use frontend\helpers\fileUtil;
use app\models\Tablefields;
use app\models\User;

/**
 * AfterchenqianController implements the CRUD actions for Afterchenqian model.
 */
class PicturelibraryController extends Controller
{
    private $tree = [
        ['table'=>'Farmerinfo','field'=>'photo','path' => '近期照片'],
        ['table'=>'Farmerinfo','field'=>'card','path' => '身份证扫描件'],
//        ['table'=>'Picturelibrary','field'=>['$classname','$field'],'path'=>'$pathname'],
    ];
    public function actionShow()
    {
        return $this->renderAjax('show');
    }

    private function showlist()
    {
        $tree = [];

        foreach (Farms::getManagementArea()['id'] as $management_area) {
            $farms = Farms::find()->where(['management_area'=>$management_area,'state'=>['1,2,3,4,5']])->all();
            foreach ($farms as $farm) {
                $temp = [];
                $tree[ManagementArea::getAreaname($management_area)][$farm['farmname'].'-'.$farm['farmername']]['farms_id'] = $farm['id'];
                $temp = $this->tree;
                $picLib = Picturelibrary::find()->where(['farms_id'=>$farm['id']])->all();
                foreach($picLib as $lib) {
                    $temp[] = ['table'=>$lib['classname'],'field'=>$lib['field'],'path'=>Tablefields::getCfields($lib['classname'], $lib['field'])];
                }

                if(!empty($temp)) {
                    $tree[ManagementArea::getAreaname($management_area)][$farm['farmname'].'-'.$farm['farmername']]['tree'] = Farms::unique_arr($temp);
                }
//                var_dump($this->tree);

            }
        }
//        var_dump($tree);
//        exit;
        return $tree;

    }

    public function actionPicturelibrarydelete($id)
    {
        $model = Picturelibrary::findOne($id);
        $farms_id = $model->farms_id;
        $pic = $model->pic;
        $pathArray = explode('/',$pic);
        $row = count($pathArray) - 1;
        $ftp = Yii::$app->ftp;
        for($i=0;$i<$row;$i++) {
            $ftp->chdir(iconv("UTF-8", "gbk//TRANSLIT", $pathArray[$i]));
        }
        $ftp->delete(iconv("UTF-8", "gbk//TRANSLIT", $pathArray[$row]));
        Logs::writeLogs('删除防火照片',$model);
        $model->delete();
        $fireModel = Fireprevention::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->one();
        $fieldpermit = Picturelibrary::find()->where(['farms_id'=>$farms_id,'field'=>'fieldpermit','year'=>User::getYear()])->count();
        if($fieldpermit) {
            $fireModel->fieldpermit = "1";
        } else {
            $fireModel->fieldpermit = "0";
        }
        $leaflets = Picturelibrary::find()->where(['farms_id'=>$farms_id,'field'=>'leaflets','year'=>User::getYear()])->count();
        if($leaflets) {
            $fireModel->leaflets = "1";
        } else {
            $fireModel->leaflets = "0";
        }
        $rectification_record = Picturelibrary::find()->where(['farms_id'=>$farms_id,'field'=>'rectification_record','year'=>User::getYear()])->count();
        if($rectification_record) {
            $fireModel->rectification_record = "1";
        } else {
            $fireModel->rectification_record = "0";
        }
//			var_dump($model);
        $fireModel->save();
        $percent = Fireprevention::getPercent($fireModel);
        $fireModel->percent = $percent;
        if($percent > 60) {
            $fireModel->finished = 1;
        }
        if($percent > 0 and $percent <= 60) {
            $fireModel->finished = 2;
        }
        if($percent == 0 or empty($percent)) {
            $fireModel->finished = 0;
        }
        $fireModel->save();

        return $this->redirect(['fireprevention/firepreventioncreate',
            'farms_id'=>$farms_id,
        ]);
    }


    public function piclist($farms_id=null,$table='all',$field=null)
    {
        $data = [];
        $alt = [];
        $t = 'Http://192.168.1.10/';
        if(!empty($farms_id)) {
            switch ($table) {
                case 'all':
                    $farm = Farms::findOne($farms_id);
                    $farmer = Farmerinfo::find()->where(['cardid' => $farm->cardid])->one();
                    $data[] = $farmer['photo'];
                    $alt[] = '法人近照';
                    $data[] = $farmer['cardpic'];
                    $alt[] = '身份证扫描件正面';
                    $data[] = $farmer['cardpicback'];
                    $alt[] = '身份证扫描件反面';
                    $pics = Picturelibrary::find()->where(['farms_id' => $farms_id])->all();
//                    var_dump($pics);exit;
                    foreach ($pics as $pic) {
                        $data[] = $t.$pic['pic'];
                        $alt[] = '所有图片';
                    }
                    break;
                case 'Farmerinfo':
                    $farm = Farms::findOne($farms_id);
                    $farmer = Farmerinfo::find()->where(['cardid' => $farm->cardid])->one();
                    if ($field == 'photo') {
                        $data[] = $t.$farmer[$field];
                        $alt[] = '法人近照';
                    } else {
                        $data[] = $t.$farmer['cardpic'];
                        $alt[] = '身份证扫描件正面';
                        $data[] = $t.$farmer['cardpicback'];
                        $alt[] = '身份证扫描件反面';
                    }

                    break;
                default:
                    $pics = Picturelibrary::find()->where(['farms_id' => $farms_id, 'classname' => $table, 'field' => $field])->all();
                    foreach ($pics as $pic) {
                        $data[] = $t.$pic['pic'];
                        $alt[] = Tablefields::getCfields($table, $field);
                    }

            }
        }
//        var_dump($alt);exit;
        return ['pics'=>$data,'alt'=>$alt];
//        echo json_encode(['pics'=>$data,'alt'=>$alt]);
    }

    public function actionShowimg($farms_id=null,$class='all',$field=NULL)
    {
        $pics = $this->piclist($farms_id,$class,$field);
//        var_dump($pics);exit;
        return $this->renderAjax('showimg',[
            'tree' => $this->showlist(),
            'farms_id' => $farms_id,
            'class' => $class,
            'field' => $field,
            'pics' => $pics,
        ]);
    }
}
