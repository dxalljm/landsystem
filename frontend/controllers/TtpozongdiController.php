<?php

namespace frontend\controllers;

use Yii;
use app\models\Theyear;
use frontend\models\theyearSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Logs;
use app\models\Reviewprocess;
use app\models\Ttpozongdi;
use app\models\Auditprocess;
use app\models\Estate;
use app\models\Farms;
use app\models\Lockedinfo;
use app\models\Zongdioffarm;
use app\models\Contractnumber;
/**
 * TheyearController implements the CRUD actions for Theyear model.
 */
class TtpozongdiController extends Controller
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
     * Updates an existing Theyear model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionTtpozongdiupdate($id)
    {
        $model = $this->findModel($id);
		
        $model->load(Yii::$app->request->post());
        $reviewprocessID = Reviewprocess::processRun ($model->auditprocess_id, $model->oldfarms_id, $model->newfarms_id );
//         var_dump($reviewprocessID);exit;
        $model->reviewprocess_id = $reviewprocessID;
        $model->state = 1;
        $model->save();
        $estateModel = new Estate();
        $estateModel->reviewprocess_id = $reviewprocessID;
        $estateModel->save();
//         	var_dump(Reviewprocess::getReturnAction ($model->auditprocess_id));exit;
//        return $this->redirect ( [ 
// 				Reviewprocess::getReturnAction ($model->auditprocess_id),
// 				'newfarmsid' => $model->newfarms_id,
// 				'oldfarmsid' => $model->oldfarms_id,
// 				'reviewprocessid' => $reviewprocessID,
//        			'process' => Auditprocess::find()->where(['id'=>$model->auditprocess_id])->one()['actionname']
//        	] );
		return $this->redirect(['farms/farmsttpozongdiview','id'=>$id]);
//         } else {
//             return $this->render('ttpozongdiupdate', [
//                 'model' => $model,
//             ]);
//         }
    }

    public function actionTtpozongdicreate($id)
    {
    	$model = $this->findModel($id);
    	$model->state = 1;
    	$model->save();
    	return $this->redirect(['farms/farmsttpomenu', 'farms_id'=>$model->oldfarms_id]);
    }
    
    /**
     * Deletes an existing Theyear model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionTtpozongdidelete($id)
    {
        $model = $this->findModel($id);
//         var_dump($model);exit;
    	$old = $model->attributes;
    	Logs::writeLog('删除转让信息',$id,$old);
       
		$oldfarms = Farms::findOne($model->oldfarms_id);
		$oldfarms->locked = 0;
		$oldfarms->save();
		
		$newfarms = Farms::findOne($model->newfarms_id);
		$new = $newfarms->attributes;
		if($model->actionname == 'farmssplit' or $model->actionname == 'farmstransfer') {			
			$newfarms->delete();
		}
		if($model->actionname == 'farmstozongdi') {
			$newfarms->state = 1;
			$newfarms->locked = 0;
			$newfarms->save();		
		}
// 		if($model->actionname == 'farmstozongdi') {
// 			Zongdioffarm::zongdiDelete($model->newfarms_id, $model->ttpozongdi);
// 			Zongdioffarm::zongdiUpdate($model->oldfarms_id, $model->oldzongdi);
// 		} else {
			Zongdioffarm::zongdiUpdate($model->newfarms_id,$model->newnewfarms_id ,$model->newzongdi);
			Zongdioffarm::zongdiUpdate($model->oldfarms_id,$model->oldnewfarms_id, $model->oldzongdi);
// 		}
		
		$lockedinfo = Lockedinfo::find()->where(['farms_id'=>$model->oldfarms_id])->one();
		$lockedinfoModel = Lockedinfo::findOne($lockedinfo['id']);
		$lockedinfoModel->delete();
		$lockedinfo = Lockedinfo::find()->where(['farms_id'=>$model->newfarms_id])->one();
		if($lockedinfo) {
			$lockedinfoModel = Lockedinfo::findOne($lockedinfo['id']);
			$lockedinfoModel->delete();
		}
		$model->delete();
		Contractnumber::contractnumberSub();
        return $this->redirect(['farms/farmsmenu', 'farms_id'=>$model->oldfarms_id]);
    }

    /**
     * Finds the Theyear model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Theyear the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ttpozongdi::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
