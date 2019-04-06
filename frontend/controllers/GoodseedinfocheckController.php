<?php

namespace frontend\controllers;

use app\models\Farms;
use Yii;
use app\models\Goodseedinfocheck;
use frontend\models\GoodseedinfocheckSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;
use app\models\Plantingstructurecheck;

/**
 * GoodseedinfocheckController implements the CRUD actions for Goodseedinfocheck model.
 */
class GoodseedinfocheckController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Goodseedinfocheck models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GoodseedinfocheckSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionGoodseedinfochecksave2($typename,$area,$planting_id)
    {
        $planting = Plantingstructurecheck::findOne($planting_id);
        $typeArray = explode(',',$typename);
        $areaArray = explode(',',$area);
        $types = [];
        $areas = [];
        for($i=0;$i<count($typeArray);$i++) {
            if(!empty($typeArray[$i])) {
                $types[] = $typeArray[$i];
            }
            if(!empty($areaArray[$i])) {
                $areas[] = $areaArray[$i];
            }
        }
        $data = $this->sameToSum($types,$areas);
        $ginfos = Goodseedinfocheck::find()->where(['planting_id'=>$planting['id'],'year'=>User::getYear()])->all();
        if($ginfos) {
            foreach ($ginfos as $ginfo) {
                $model = Goodseedinfocheck::findOne($ginfo['id']);
                Logs::writeLogs('删除良种信息',$model);
                $model->delete();
            }
        }
        $farm = Farms::findOne($planting['farms_id']);
//        var_dump($data);
        $s = 0;
        foreach ($data as $type_id => $area_num) {
            $model = new Goodseedinfocheck();
            $model->farms_id = $planting['farms_id'];
            $model->management_area = $planting['management_area'];
            $model->lease_id = $planting['lease_id'];
            $model->planting_id = $planting['id'];
            $model->plant_id = $planting['plant_id'];
            $model->goodseed_id = $type_id;
            $model->area = $area_num;
            $model->create_at = time();
            $model->update_at = $model->create_at;
            $model->year = $planting['year'];
            $model->total_area = $planting['area'];
            $model->contractarea = $farm['contractarea'];
            $model->contractnumber = $farm['contractnumber'];
            $model->farmstate = $farm['state'];
            $save = $model->save();
            if($save) {
                ++$s;
            }
//            var_dump($model->getErrors());

        }
        echo json_encode(['save'=>$s]);
    }

    public function actionGoodseedinfocheckchecksave($typename,$area,$plant_id,$farms_id,$lease_id,$total_area)
    {
        $typeArray = explode(',',$typename);
        $areaArray = explode(',',$area);
        $types = [];
        $areas = [];
        for($i=0;$i<count($typeArray);$i++) {
            if(!empty($typeArray[$i])) {
                $types[] = $typeArray[$i];
            }
            if(!empty($areaArray[$i])) {
                $areas[] = $areaArray[$i];
            }
        }
        $farm = Farms::findOne($farms_id);
        $data = $this->sameToSum($types,$areas);
        $id = [];
//        var_dump($data);
//        var_dump($lease_id);
        foreach ($data as $type_id => $area_num) {

            $model = Goodseedinfocheck::find()->where(['plant_id'=>$plant_id,'total_area'=>$total_area,'farms_id'=>$farms_id,'goodseed_id'=>$type_id])->one();
            if($model) {
                $model->delete();
            }
            $model = new Goodseedinfocheck();
            $model->farms_id = $farms_id;
            $model->management_area = $farm['management_area'];
            $model->lease_id = $lease_id;
            $model->planting_id = null;
            $model->plant_id = $plant_id;
            $model->goodseed_id = $type_id;
            $model->area = $area_num;
            $model->create_at = time();
            $model->update_at = $model->create_at;
            $model->year = User::getYear();
            $model->total_area = $total_area;
            $save = $model->save();
//            var_dump($model->getErrors());
            $id[] = $model->id;
        }
//        var_dump($id);exit;
        $goodseedinfo_id = implode(',',$id);
        echo json_encode(['save'=>$save,'goodseedinfo_id'=>$goodseedinfo_id]);
    }

    public function actionGoodseedinfocheckdelete($id)
    {
        $d = '';
        $model = Goodseedinfocheck::findOne($id);
        if($model) {
            $d = $model->delete();
        }
        echo json_encode(['state'=>$d]);
    }

    public function actionGoodseedinfochecklistajax($farms_id,$planting_id,$plant_id)
    {
        $info = Goodseedinfocheck::find()->where(['farms_id'=>$farms_id,'planting_id'=>$planting_id,'plant_id'=>$plant_id,'year'=>User::getYear()]);
        return $this->renderAjax('goodseedinfochecklistajax',
            [
                'goodseedinfo' => $info->all(),
                'goodseedarea' => sprintf('%.2f',$info->sum('area')),
                'plantarea' => Plantingstructurecheck::find()->where(['id'=>$planting_id])->one()['area'],
                'plant_id' => $plant_id,
                'planting_id' => $planting_id,
            ]);
    }

    private function sameToSum($types,$areas)
    {
//        var_dump($areas);
//        var_dump($types);exit;
        $data = [];
        for($i=0;$i<count($types);$i++) {
            if(isset($data[$types[$i]])) {
                $data[$types[$i]] += $areas[$i];
            } else {
                $data[$types[$i]] = $areas[$i];
            }
        }
        return $data;
    }

    /**
     * Displays a single Goodseedinfocheck model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Goodseedinfocheck model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Goodseedinfocheck();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Goodseedinfocheck model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Goodseedinfocheck model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Goodseedinfocheck model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Goodseedinfocheck the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Goodseedinfocheck::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
