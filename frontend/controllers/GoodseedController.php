<?php

namespace frontend\controllers;

use app\models\Goodseedinfo;
use app\models\User;
use app\models\Plantingstructure;
use Yii;
use app\models\Goodseed;
use frontend\models\goodseedSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Plant;
use app\models\Logs;
/**
 * GoodseedController implements the CRUD actions for Goodseed model.
 */
class GoodseedController extends Controller
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
    public function beforeAction($action)
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/logout']);
        } else {
            return true;
        }
    }
//     public function beforeAction($action)
//     {
//     	$action = Yii::$app->controller->action->id;
//     	if(\Yii::$app->user->can($action)){
//     		return true;
//     	}else{
//     		throw new \yii\web\UnauthorizedHttpException('对不起，您现在还没获此操作的权限');
//     	}
//     }

    /**
     * Lists all Goodseed models.
     * @return mixed
     */
    public function actionGoodseedindex()
    {
        $searchModel = new goodseedSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		Logs::writeLog('良种信息');
        return $this->render('goodseedindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Goodseed model.
     * @param integer $id
     * @return mixed
     */
    public function actionGoodseedview($id)
    {
        $model = $this->findModel($id);
    	Logs::writeLogs('查看良种信息',$model);
        return $this->render('goodseedview', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Goodseed model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionGoodseedcreate()
    {
        $model = new Goodseed();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	Logs::writeLogs('创建良种信息',$model);
            return $this->redirect(['goodseedview', 'id' => $model->id]);
        } else {
            return $this->render('goodseedcreate', [
                'model' => $model,
            ]);
        }
    }
    //获取良种信息
    public function  actionGoodseedgetmodel($plant_id)
    {
        $goodseed = Goodseed::find()->where(['plant_id'=>$plant_id])->all();
        $newData = NULL;
        foreach($goodseed as $key=>$val){
            $newData[$key] = $val->attributes;
        }
        echo json_encode(['status'=>1,'goodseed'=>$newData]);
    }

    public function  actionGoodseedsave($typename_id,$plant_id)
    {
//        $goodseed = Goodseed::find()->where(['typename'=>$typename])->one();
//        if(empty($goodseed)) {
//            $model = new Goodseed();
//            $model->plant_id = $plant_id;
//            $model->typename = $typename;
//            $model->save();
//            $goodseed_id = $model->id;
//        } else {
//            $goodseed_id = $goodseed['id'];
//        }
//        $planting = Plantingstructure::find
//        $goodseedinfo = Goodseedinfo::find()->where([''])
//        echo json_encode(['goodseed_id'=>$goodseed_id]);
    }

    public function actionGetgoodseed($plant_id)
    {
        $goodseed = Goodseed::find()->where(['plant_id'=>$plant_id])->one();
        if($goodseed) {
            return true;
        }
        return false;
    }

    public function  actionGoodseedlistajax($farms_id,$plant_id,$planter,$type,$input,$id)
    {
        $goodseedtypename = '';
        $goodseed = Goodseed::find()->where(['plant_id'=>$plant_id])->all();
        $newData = ArrayHelper::map($goodseed,'id','typename');
        $goodseed_id = Plantingstructure::find()->where(['farms_id'=>$farms_id,'plant_id'=>$plant_id,'lease_id'=>$planter,'year'=>User::getYear()])->one();

        if($goodseed_id) {
            $goodseedtypename = Goodseed::find()->where(['id'=>$goodseed_id['goodseed_id']])->one()['typename'];
        }

        $goodseedinfo = Goodseedinfo::find()->where(['farms_id'=>$farms_id,'total_area'=>$input])->all();
        if($goodseedinfo) {
            foreach ($goodseedinfo as $key => $value) {
                $input -= $value['area'];
            }
        }
//        var_dump($goodseedinfo);exit;

        return $this->renderAjax('goodseedlistajax',
            [
                'goodseedlist'=>$newData,
                'plant_id'=>$plant_id,
                'planter' => $planter,
                'type' => $type,
                'goodseedtypename' => $goodseedtypename,
                'goodseedinfo' => $goodseedinfo,
                'goodseed_id' => $goodseed_id,
                'input' => sprintf('%.2f',$input),
                'id' => $id,
            ]);
    }

    /**
     * Updates an existing Goodseed model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionGoodseedupdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	Logs::writeLogs('更新良种信息',$model);
            return $this->redirect(['goodseedview', 'id' => $model->id]);
        } else {
            return $this->render('goodseedupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Goodseed model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionGoodseeddelete($id)
    {
    	$model = $this->findModel($id);
        $model->delete();
        Logs::writeLogs('删除良种信息',$model);
        return $this->redirect(['goodseedindex']);
    }

    
    /**
     * Finds the Goodseed model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Goodseed the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Goodseed::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
