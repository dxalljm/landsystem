<?php

namespace frontend\controllers;

use Yii;
use app\models\Machinetype;
use frontend\models\MachinetypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\UploadForm;
use yii\web\UploadedFile;
/**
 * MachinetypeController implements the CRUD actions for Machinetype model.
 */
class MachinetypeController extends Controller
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
     * Lists all Machinetype models.
     * @return mixed
     */
    public function actionMachinetypeindex()
    {
        $searchModel = new MachinetypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('machinetypeindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Machinetype model.
     * @param integer $id
     * @return mixed
     */
    public function actionMachinetypeview($id)
    {
        return $this->render('machinetypeview', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Machinetype model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionMachinetypecreate()
    {
    	
        $model = new Machinetype();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['machinetypeview', 'id' => $model->id]);
        } else {
            return $this->render('machinetypecreate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Machinetype model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionMachinetypeupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['machinetypeview', 'id' => $model->id]);
        } else {
            return $this->render('machinetypeupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Machinetype model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionMachinetypedelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['machinetypeindex']);
    }

    public function actionMachinetypexls()
    {
    	set_time_limit(0);
    	$model = new UploadForm();
    	//echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";
    	
    	if (Yii::$app->request->isPost) {
    		$model->file = UploadedFile::getInstance($model, 'file');
    	
    		if ($model->file && $model->validate()) {
    	
    			$xlsName = time().rand(100,999);
    			$model->file->name = $xlsName;
    			$model->file->saveAs('uploads/' . $model->file->name . '.' . $model->file->extension);
    			$path = 'uploads/' . $model->file->name . '.' . $model->file->extension;
    			$loadxls = \PHPExcel_IOFactory::load($path);
    			for($i=2;$i<=$loadxls->getActiveSheet()->getHighestRow();$i++) {
    				//echo $loadxls->getActiveSheet()->getCell('A'.$i)->getValue()."<br>";
    				$B = $loadxls->getActiveSheet()->getCell('B'.$i)->getValue();
    				$C = $loadxls->getActiveSheet()->getCell('C'.$i)->getValue();
    				$D = $loadxls->getActiveSheet()->getCell('D'.$i)->getValue();
    				if(!($b = Machinetype::find()->where(['typename'=>$B])->one())) {	
    					$machinetypeModel = new Machinetype();
    					$machinetypeModel->father_id = 0;
    					$machinetypeModel->typename = $B;
    					$machinetypeModel->save();
    				} else {
    					if(!($c = Machinetype::find()->where(['typename'=>$C])->one())) {
    						$machinetypeModel = new Machinetype();
    						$machinetypeModel->father_id = $b->id;
    						$machinetypeModel->typename = $C;
    						$machinetypeModel->save();
    					} else {
    						if(!($d = Machinetype::find()->where(['typename'=>$D])->one())) {
    							$machinetypeModel = new Machinetype();
    							$machinetypeModel->father_id = $c->id;
    							$machinetypeModel->typename = $D;
    							$machinetypeModel->save();
    						}					
    					}
    				}
    			}
//     				$new = $machinetypeModel->attributes;
//     				Logs::writeLog('xls批量导入创建宗地信息',$machinetypeModel->id,'',$new);
    				//print_r($machinetypeModel->getErrors());
    			}
    		}
    	
    	 
    	return $this->render('machinetypexls',[
    			'model' => $model,
    	]);
    }
    
    public function actionGetsmallclass($father_id)
    {
    	$newData = [];
    	$machinetype = Machinetype::find()->where(['father_id'=>$father_id])->all();
//     	var_dump($machinetype);exit;
    	foreach ($machinetype as $key => $value) {
    		$newData[] = $value->attributes;
    	}
    	echo json_encode(['status'=>1,'data'=>$newData]);
    }
    /**
     * Finds the Machinetype model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Machinetype the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Machinetype::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
