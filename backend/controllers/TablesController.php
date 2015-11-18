<?php

namespace backend\controllers;
use Yii;
use app\models\tables;
use backend\models\tablesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Migration;
use yii\filters\AccessControl;
/**
 * TablesController implements the CRUD actions for tables model.
 */
class TablesController extends Controller
{
    public function behaviors()
    {
        return [
			'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['tablescreate', 'tablesupdate', 'tablesview', 'tablesindex', 'tablesdelete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
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
     * Lists all tables models.
     * @return mixed
     */
    public function actionTablesindex()
    {
        $searchModel = new tablesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('tablesindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single tables model.
     * @param integer $id
     * @return mixed
     */
    public function actionTablesview($id)
    {
        return $this->render('tablesview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new tables model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionTablescreate()
    {
        $model = new tables();
		
        if ($model->load(Yii::$app->request->post())) {
			if(Tables::find()->where(['tablename'=>$model->tablename])->one()) {
				return $this->render('error', [
                	'message' => '该表已经存在，不能被创建！',
            	]);
			} else {
				$mir = new Migration();  
				$sch = new \yii\db\mysql\Schema;  
				$mir->createTable($mir->db->tablePrefix.$model->tablename, ['id' => 'pk',]);    
			    $model->save(); 
            	return $this->redirect(['tablesview', 'id' => $model->id]);
			}
        } else {
            return $this->render('tablescreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing tables model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionTablesupdate($id)
    {
        $model = $this->findModel($id);
		$modelold = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
        	if($model->tablename == $modelold->tablename) {
        		$model->save();
        		return $this->redirect(['tablesview', 'id' => $model->id]);
        	} else {
				$mir = new Migration();  
				$sch = new \yii\db\mysql\Schema;  
				//print_r($mir->db->tablePrefix);
				$mir->renameTable($mir->db->tablePrefix.$modelold->tablename,$mir->db->tablePrefix.$model->tablename);
				$model->save();
	            return $this->redirect(['tablesview', 'id' => $model->id]);
        	}
        } else {
            return $this->render('tablesupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing tables model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionTablesdelete($id,$farms_id)
    {
		$t = $this->findModel($id);
        $this->findModel($id)->delete();
		$mir = new Migration();  
		$sch = new \yii\db\mysql\Schema;  
		//print_r($t->tablename);
		$mir->dropTable($mir->db->tablePrefix.$t->tablename);           
        return $this->redirect(['tablesindex']);
    }

    /**
     * Finds the tables model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return tables the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = tables::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	public function actionTableserror($id)
	{
		$errors = ['该表已经存在，不能再次创建！'];
		return $this->redirect(['error']);
	}
}
