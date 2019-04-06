<?php

namespace frontend\controllers;

use app\models\ManagementArea;
use app\models\Plantingstructureyearfarmsid;
use app\models\Plantingstructurecheck;
use app\models\Farms;
use frontend\helpers\Pinyin;
use frontend\helpers\whereHandle;
use frontend\models\farmsSearch;
use Yii;
use app\models\Draw;
use frontend\models\DrawSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;

/**
 * DrawController implements the CRUD actions for Draw model.
 */
class DrawController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Draw models.
     * @return mixed
     */
    public function actionDrawindex()
    {
        $searchModel = new DrawSearch();
        $params = Yii::$app->request->queryParams;
        $params['DrawSearch']['year'] = User::getYear();
        $params['DrawSearch']['state'] = 1;
        $dataProvider = $searchModel->search($params);

        return $this->render('drawindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDrawreset()
    {
        $data = Draw::find()->where(['year'=>User::getYear()])->all();
        foreach ($data as $value) {
            $model = Draw::findOne($value['id']);
            $model->delete();
        }
        echo json_encode(['state'=>true]);
    }

    public function actionDrawtoxls($where)
    {
        $whereArray = json_decode($where,true);
        $result = [];
        $data = Draw::find()->where($whereArray)->all();
        $i = 0;
        $farmsAllid = [];
        foreach ($data as $key => $val) {
            $content = '';
            //获取法人种植信息
            $farm = Farms::findOne($val['farms_id']);
            $result[] = [
                    'row' => ++$i,
                    'management_area' => ManagementArea::getAreanameOne($farm['management_area']),
                    'farmname' => $farm['farmname'],
                    'farmername' => $farm['farmername'],
                    'contractnumber' => $farm['contractnumber'],
                    'content' => $content,
                ];
        }
        return $this->render('drawtoxls', [
            'result' => $result,
            'areaname' => Farms::getManagementArea()['areaname'],
        ]);
    }

    public function actionDrawdeleteall($management_area)
    {
        $data = Draw::find()->where(['management_area'=>$management_area,'state'=>2])->all();
        foreach ($data as $value) {
            $model = Draw::findOne($value['id']);
            $model->delete();
        }
    }

    public function actionGetfarms($management_area)
    {
        $checks = Plantingstructurecheck::find()->where(['management_area'=>$management_area,'plant_id'=>[3,6]])->all();
        $info = [];

        $allid = [];
        $allcardid = [];
        foreach ($checks as $check) {
            $allid[] = $check['farms_id'];
        }
        $newid = [];
        $newcardid = [];
        $newid = array_unique($allid);
        foreach ($newid as $id) {
            $farm = Farms::findOne($id);
            $allcardid[] = $farm['cardid'];
        }
        $newcardid = array_unique($allcardid);
//        var_dump($newid);
//        foreach ($newcardid as $cardid) {
//            $farm = Farms::find()->where(['cardid'=>$cardid,'management_area'=>$management_area])->one();
//            $newid[] = $farm['id'];
//        }
        foreach ($newcardid as $cardid) {
            $farminfo = Farms::find()->where(['cardid'=>$cardid,'management_area'=>$management_area])->all();
            $rid = [];
            $farms_id = '';
//            if(count($farminfo) > 1) {
                foreach ($farminfo as $value) {
                    $rid[] = $value['id'];
                }
//            }
//            var_dump($rid);
            $roundid = rand(0,count($rid)-1);
//            var_dump($rid[$roundid]);
            $farm = Farms::findOne($rid[$roundid]);
            $info[] = [
                'id' => $farm['id'],
                'management_area' => $farm['management_area'],
                'farmname' => $farm['farmname'],
                'farmername' => $farm['farmername'],
                'cardid' => $farm['cardid'],
            ];
        }
//        var_dump($info);exit;
        echo json_encode($info);
    }

    public function getFarms($management_area)
    {
        $checks = Plantingstructurecheck::find()->where(['management_area'=>$management_area,'plant_id'=>[3,6]])->all();
        $info = [];

        $allid = [];
        $allcardid = [];
        foreach ($checks as $check) {
            $allid[] = $check['farms_id'];
        }
        $newid = array_unique($allid);
        foreach ($newid as $id) {
            $farm = Farms::findOne($id);
            $allcardid[] = $farm['cardid'];
        }
        $newcardid = array_unique($allcardid);
        foreach ($newcardid as $cardid) {
            $farminfo = Farms::find()->where(['cardid'=>$cardid,'management_area'=>$management_area])->all();
            $rid = [];
            foreach ($farminfo as $value) {
                $rid[] = $value['id'];
            }
            $roundid = rand(0,count($rid)-1);
            $farm = Farms::findOne($rid[$roundid]);
            $info[] = [
                'id' => $farm['id'],
                'management_area' => $farm['management_area'],
                'farmname' => $farm['farmname'],
                'farmername' => $farm['farmername'],
                'cardid' => $farm['cardid'],
            ];
        }
        return $info;
    }

    public function actionIscardid($cardid,$farms_id)
    {
        $farm = Farms::findOne($farms_id);
        $draw = Draw::find()->where(['cardid'=>$cardid,'management_area'=>$farm['management_area']])->one();
//        var_dump($draw);exit;
        if($draw) {
//            $draw->delete();
            echo json_encode(['state'=>false]);
        } else {

            $model = new Draw();
            $model->farms_id = $farms_id;
            $model->management_area = $farm['management_area'];
            $model->create_at = time();
            $model->year = User::getYear();
            $model->state = 2;
            $model->cardid = $farm['cardid'];
            $save = $model->save();
            echo json_encode(['state'=>$save,'farms_id'=>$farms_id]);
        }
    }

    //随机取农场10%个数
    public function actionDrawrand($management_area,$now)
    {
        $arrayNum = [];
        $data = $this->getFarms($management_area);
        $nums = $this->getManagementrows($management_area);
        while (count($arrayNum) < $nums) {
            $key = rand(0,count($data)-1);
            if(!in_array($key,$arrayNum)) {
                $arrayNum[] = $key;
            }
        }
        echo json_encode(['arrayNum'=>$arrayNum]);
    }

    public function actionDrawselect($management_area,$j=null)
    {
        $farms = Plantingstructureyearfarmsid::find()->where(['management_area'=>$management_area])->all();
        $allid = [];
        foreach ($farms as $farm) {
            $allid[] = $farm['farms_id'];
        }
        $raws = count($allid);
        $key = rand(0,$raws);
        $farminfo = Farms::findOne($allid[$key]);
        $info = [
            'id' => $farminfo['id'],
            'farmname' => $farminfo['farmname'],
            'farmername' => $farminfo['farmername'],
//            'areapinyin' => Pinyin::encode(Farms::getManagementArea('small')['areaname'][$farminfo['management_area']-1]),
            'raw' => round($raws*0.1),
            'j' => $j,
        ];
        echo json_encode($info);
    }

    public function actionDrawsave($allid,$management_area)
    {
//        var_dump($allid);
        $arrayid = explode(',',$allid);
//        var_dump($arrayid);exit;
        foreach ($arrayid as $farms_id) {
            if(!empty($farms_id)) {
                $farm = Farms::findOne($farms_id);
                $model = new Draw();
                $model->farms_id = $farms_id;
                $model->management_area = $farm['management_area'];
                $model->create_at = time();
                $model->year = User::getYear();
                $model->state = 1;
                $model->cardid = $farm['cardid'];
                $save = $model->save();
            }
        }
        echo json_encode(['state'=>true]);
    }

    public function actionGetmsg($management_area)
    {
        $farms = Farms::find()->where(['management_area'=>$management_area,'state'=>[1,2,3,4,5]])->all();
        $allid = [];
        foreach ($farms as $farm) {
            $allid[] = $farm['id'];
        }
        $raws = count($allid);
        $key = rand(0,$raws);
        $farminfo = Farms::findOne($allid[$key]);
        $info = [
            'areaname' => ManagementArea::getAreanameOne($management_area),
            'msg1' => '共有农场'.$raws.'户',
            'msg3' => '法人'.Farms::getManagementAreaFarmerrows($management_area).'人',
            'msg2' => '随机抽取10%,共'.round($raws*0.1).'户',
            'raw' => round($raws*0.1),
        ];
        echo json_encode($info);
    }

    public function actionManagementrows($id)
    {
        $count = Farms::find()->where(['management_area' => $id, 'state' => [1, 2, 3, 4, 5]])->count();
        $result = round($count * 0.1);
        echo json_encode($result);
    }

    public function getManagementrows($id)
    {
        $count = Farms::find()->where(['management_area' => $id, 'state' => [1, 2, 3, 4, 5]])->count();
        $result = round($count * 0.1);
        return $result;
    }

    /**
     * Displays a single Draw model.
     * @param integer $id
     * @return mixed
     */
    public function actionDrawview($id)
    {
        return $this->render('drawview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Draw model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionDrawcreate()
    {
        $model = new Draw();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['drawview', 'id' => $model->id]);
        } else {
            return $this->render('drawcreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Draw model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDrawupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['drawview', 'id' => $model->id]);
        } else {
            return $this->render('drawupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Draw model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDrawdelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['drawindex']);
    }

    /**
     * Finds the Draw model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Draw the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Draw::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
