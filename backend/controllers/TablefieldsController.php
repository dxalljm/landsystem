<?php

namespace backend\controllers;

use Yii;
use app\models\tablefields;
use app\models\tables;
use app\models\farms;
use backend\models\tablefieldsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Migration;
use yii\filters\AccessControl;
use app\models\Loan;
/**
 * TablefieldsController implements the CRUD actions for tablefields model.
 */
class TablefieldsController extends Controller
{
	private $db;
	
    public function behaviors()
    {
         return [
			'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['tablefieldscreate', 'tablefieldsupdate', 'tablefieldsview', 'tablefieldsindex', 'tablefieldsdelete'],
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
    public function beforeAction($action)
    {
    	$action = Yii::$app->controller->action->id;
    	if(\Yii::$app->user->can($action)){
    		return true;
    	}else{
    		throw new \yii\web\UnauthorizedHttpException('对不起，您现在还没获此操作的权限');
    	}
    }

    /**
     * Lists all tablefields models.
     * @return mixed
     */
    public function actionTablefieldsindex($id)
    {
    	$this->layout='@app/views/layouts/nomain.php';
        $searchModel = new tablefieldsSearch();
        $dataProvider = $searchModel->search(["tables_id"=>$id]);

        return $this->render('tablefieldsindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single tablefields model.
     * @param integer $id
     * @return mixed
     */
    public function actionTablefieldsview($id)
    {
    	$this->layout='@app/views/layouts/nomain.php';
        return $this->render('tablefieldsview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new tablefields model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionTablefieldscreate($tables_id)
    {
    	$this->layout='@app/views/layouts/nomain.php';
        $model = new tablefields();
        
        $mir = new Migration();

        $this->db = $mir->db;
        $sch = new \yii\db\mysql\Schema;
        if ($model->load(Yii::$app->request->post())) {
        	$tablename = tables::find()->where(['id'=>$model->tables_id])->one()['tablename'];
        	$columns = $this->getColumns($tablename);
//         	var_dump($this->getTableColumns());exit;
			if($this->isIn($model->fields, $columns)) {
				return $this->render('tablefieldserror', [
                	'message' => '该表项已经存在，不能被创建！',
            	]);
			} else {
				$table = Tables::find()->where(['id'=>$model->tables_id])->one(); 
				$mir->addColumn($mir->db->tablePrefix.$table->tablename, $model->fields, $model->type);    
			    $model->save(); 
            	return $this->redirect(['tablefieldsview', 'id' => $model->id]);
			}
        } else {
            return $this->render('tablefieldscreate', [
                'model' => $model,
            ]);
        }
    }
	
    public function isIn($str,$arr)
    {
    	foreach($arr as $val)
    	{
    		//echo $val[''];
    		if($val['COLUMN_NAME'] == $str) {
    			return true;
    			//break;
    		}
    			
    	}
    	return false;
    }
    
	public function getColumns($table)
    {
    	//echo "    > get Column $table ...";
    	//$time = microtime(true);
    	$sql = "select COLUMN_NAME from information_schema.COLUMNS where table_name = '".$this->db->tablePrefix.$table."'";
    	$command = $this->db->createCommand($sql);
    	$result = $command->queryAll();
    	return $result;
    	//$this->db->createCommand()->dropIndex($name, $table)->execute();
    	//echo " done (time: " . sprintf('%.3f', microtime(true) - $time) . "s)\n";
    }
    /**
     * Updates an existing tablefields model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionTablefieldsupdate($id)
    {
    	$this->layout='@app/views/layouts/nomain.php';
        $model = $this->findModel($id);
		$modelold = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
			$table = Tables::find()->where(['id'=>$model->tables_id])->one();
			$mir = new Migration();  
			$sch = new \yii\db\mysql\Schema;  
			$mir->renameColumn($mir->db->tablePrefix.$table->tablename, $modelold->fields, $model->fields, $model->type);    
			 $model->save();
            return $this->redirect(['tablefieldsindex', 'id' => $model->tables_id]);
        } else {
            return $this->render('tablefieldsupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing tablefields model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionTablefieldsdelete($id)
    {
    	$this->layout='@app/views/layouts/nomain.php';
        $model = $this->findModel($id);
        $table = Tables::find()->where(['id'=>$model->tables_id])->one();
        $mir = new Migration();
        $sch = new \yii\db\mysql\Schema;
        $bool = $mir->dropColumn($mir->db->tablePrefix.$table->tablename,$model->fields);
       	$this->findModel($id)->delete();
       	return $this->redirect(['tablefieldsindex','id'=>$_GET['tables_id']]);    
    }

    /**
     * Finds the tablefields model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return tablefields the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = tablefields::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
