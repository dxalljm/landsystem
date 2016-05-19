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
        $reviewprocessID = Reviewprocess::processRun ($id, $model->oldfarms_id, $model->newfarms_id );
        var_dump($reviewprocessID);exit;
        $model->reviewprocess_id = $reviewprocessID;
        $model->state = 1;
        $model->save();
//         	var_dump(Reviewprocess::getReturnAction ($model->auditprocess_id));exit;
       return $this->redirect ( [ 
				Reviewprocess::getReturnAction ($model->auditprocess_id),
				'newfarmsid' => $model->newfarms_id,
				'oldfarmsid' => $model->oldfarms_id,
				'reviewprocessid' => $reviewprocessID 
       	] );
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
        $model = $this->findModel(1);
    	$old = $model->attributes;
    	Logs::writeLog('删除年度',1,$old);
        $model->delete();

        return $this->redirect(['theyearindex']);
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
