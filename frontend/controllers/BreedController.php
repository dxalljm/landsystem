<?php

namespace frontend\controllers;

use app\models\User;
use Yii;
use app\models\Breed;
use frontend\models\breedSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Breedtype;
use app\models\Breedinfo;
use app\models\Logs;
use app\models\Farms;
/**
 * BreedController implements the CRUD actions for Breed model.
 */
class BreedController extends Controller
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
     * Lists all Breed models.
     * @return mixed
     */
    public function actionBreedindex()
    {
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/login']);
		}
    	$model = new Breed();
    	$breedtypeFather = Breedtype::find()->where(['father_id'=>1])->all();
    	$breedinfo = Breedinfo::find()->where(['breed_id'=>$loadBreed->id])->all();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['breedview', 'id' => $model->id]);
        } else {
            return $this->render('breedcreate', [
                'model' => $model,
            	'breedtypeFather' => $breedtypeFather,
            	'breedinfo' => $breedinfo,
            ]);
        }
    }

   
    /**
     * Displays a single Breed model.
     * @param integer $id
     * @return mixed
     */
    public function actionBreedview($id)
    {
        return $this->render('breedview', [
            'model' => $this->findModel($id),
        ]);
    }

    
    //删除post提交的养殖信息
    private function deleteBreedtype($nowdatabase,$postdataidarr) {
    	$databaseid = array();
    	foreach($nowdatabase as $value) {
    		$databaseid[] = $value['id'];
    	}
    	$result = array_diff($databaseid,$postdataidarr);
    	if($result) {
    		foreach($result as $val) {
    			$model = Breedinfo::findOne($val);
    			
    			$oldAttr = $model->attributes;
//     			var_dump($oldAttr);
//     			exit;
    			Logs::writeLogs('删除养殖种类信息',$model);
    			$model->delete();
    		}
    		return true;
    	} else
    		return false;
    }
    /**
     * Creates a new Breed model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionBreedcreate($farms_id)
    {
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/login']);
		}
    	$breedtypeFather = Breedtype::find()->where(['father_id'=>1])->all();
    	$breedtypePost = Yii::$app->request->post('breedtypePost');
    	$loadBreed = Breed::find()->where(['farms_id'=>$farms_id])->one();
    	if($loadBreed)
    		$breedinfo = Breedinfo::find()->where(['breed_id'=>$loadBreed->id])->all();
    	else 
    		$breedinfo = '';
    	if($loadBreed) {
    		$model = $this->findModel($loadBreed->id);
    		$old = $model->attributes;
    		if ($model->load(Yii::$app->request->post())) {
    			$model->update_at = time();
				$model->year = User::getYear();
    			$save = $model->save();
    			if($save) {
					$farmsModel = Farms::findOne($farms_id);
					$farmsModel->isbreed = 1;
					$farmsModel->save();
				}
	    		Logs::writeLogs('更新养殖信息',$model);
	    		
	    		$this->deleteBreedtype($breedinfo, $breedtypePost['id']);
	    		if ($breedtypePost) {
	    			$old = '';
	    			for($i=1;$i<count($breedtypePost['breedtype_id']);$i++) {
	    				if(Breedinfo::findOne($breedtypePost['id'][$i])) {
	    					$breedinfoModel = Breedinfo::findOne($breedtypePost['id'][$i]);
	    					$old = $breedinfoModel->attributes;
	    					$breedinfoModel->update_at = time();
	    				}
	    				else {
	    					$breedinfoModel = new Breedinfo();
	    					$breedinfoModel->create_at = time();
	    					$breedinfoModel->update_at = $breedinfoModel->create_at;
	    					$breedinfoModel->management_area = Farms::getFarmsAreaID($farms_id);
	    					$breedinfoModel->farms_id = $farms_id;
	    				}
	    				
	    				$breedinfoModel->breed_id = $model->id;
	    				$breedinfoModel->number = (int)$breedtypePost['number'][$i];
	    				$breedinfoModel->basicinvestment = (float)$breedtypePost['basicinvestment'][$i];
	    				$breedinfoModel->housingarea = (float)$breedtypePost['housingarea'][$i];
	    				$breedinfoModel->breedtype_id = (int)$breedtypePost['breedtype_id'][$i];
						$breedinfoModel->farms_id = $farms_id;
						$breedinfoModel->year = User::getYear();
	    				$breedinfoModel->save();
	    				Logs::writeLogs('更新养殖种类信息',$breedinfoModel);
	    			}
	    		}
	    		if($loadBreed)
    				$breedinfo = Breedinfo::find()->where(['breed_id'=>$loadBreed->id])->all();
	    		else 
	    			$breedinfo = '';
	    		return $this->render('breedcreate', [
	    				'model' => $model,
	    				'breedtypeFather' => $breedtypeFather,
	    				'breedinfo' => $breedinfo,
	    		]);
        		
        	}
    	}
    	else { 
        	$model = new Breed();
        	if ($model->load(Yii::$app->request->post())) {
        		$model->management_area = Farms::getFarmsAreaID($farms_id);
        		$model->create_at = time();
        		$model->update_at = $model->create_at;
				$model->year = User::getYear();
        		$save = $model->save();
				if($save) {
					$farmsModel = Farms::findOne($farms_id);
					$farmsModel->isbreed = 1;
					$farmsModel->save();
				}
        		Logs::writeLogs('创建养殖信息',$model);
        		if ($breedtypePost) {
        			//var_dump($parmembers);
        			for($i=1;$i<count($breedtypePost['breedtype_id']);$i++) {
        				$breedinfoModel = new Breedinfo();
        				$breedinfoModel->breed_id = $model->id;
        				$breedinfoModel->management_area = Farms::getFarmsAreaID($farms_id);
        				$breedinfoModel->number = (int)$breedtypePost['number'][$i];
        				$breedinfoModel->basicinvestment = (float)$breedtypePost['basicinvestment'][$i];
        				$breedinfoModel->housingarea = (float)$breedtypePost['housingarea'][$i];
        				$breedinfoModel->breedtype_id = (int)$breedtypePost['breedtype_id'][$i];
        				$breedinfoModel->create_at = time();
        				$breedinfoModel->update_at = $breedinfoModel->create_at;
						$breedinfoModel->year = User::getYear();
						$breedinfoModel->farms_id = $farms_id;
        				$breedinfoModel->save();
        				Logs::writeLogs('新增养殖各类信息',$breedinfoModel);
        			}
        		}
        		//return $this->redirect(['breedview', 'id' => $model->id,'farms_id'=>$farms_id]);
        		
    				$breedinfo = Breedinfo::find()->where(['breed_id'=>$model->id])->all();
	    		
        		return $this->render('breedcreate', [
        				'model' => $model,
        				'breedtypeFather' => $breedtypeFather,
        				'breedinfo' => $breedinfo,
        		]);
        	}
    	}   
    	return $this->render('breedcreate', [
    			'model' => $model,
    			'breedtypeFather' => $breedtypeFather,
    			'breedinfo' => $breedinfo,
    	]);
    }

	public function actionBreedcreateajax($farms_id)
	{
		if(Yii::$app->user->isGuest) {
			return $this->redirect(['site/login']);
		}
		$breedtypeFather = Breedtype::find()->where(['father_id'=>1])->all();
		$breedtypePost = Yii::$app->request->post('breedtypePost');
		$loadBreed = Breed::find()->where(['farms_id'=>$farms_id])->one();
		if($loadBreed)
			$breedinfo = Breedinfo::find()->where(['breed_id'=>$loadBreed->id])->all();
		else
			$breedinfo = '';
		if($loadBreed) {
			$model = $this->findModel($loadBreed->id);
			$old = $model->attributes;
			if ($model->load(Yii::$app->request->post())) {
				$model->update_at = time();
				$model->year = User::getYear();
				$model->save();
				$new = $model->attributes;
				Logs::writeLogs('更新养殖信息',$loadBreed);

				$this->deleteBreedtype($breedinfo, $breedtypePost['id']);
				if ($breedtypePost) {
					$old = '';
					for($i=1;$i<count($breedtypePost['breedtype_id']);$i++) {
						if(Breedinfo::findOne($breedtypePost['id'][$i])) {
							$breedinfoModel = Breedinfo::findOne($breedtypePost['id'][$i]);
							$old = $breedinfoModel->attributes;
							$breedinfoModel->update_at = time();
						}
						else {
							$breedinfoModel = new Breedinfo();
							$breedinfoModel->create_at = time();
							$breedinfoModel->update_at = $breedinfoModel->create_at;
							$breedinfoModel->management_area = Farms::getFarmsAreaID($farms_id);
							$breedinfoModel->farms_id = $farms_id;
						}

						$breedinfoModel->breed_id = $model->id;
						$breedinfoModel->number = (int)$breedtypePost['number'][$i];
						$breedinfoModel->basicinvestment = (float)$breedtypePost['basicinvestment'][$i];
						$breedinfoModel->housingarea = (float)$breedtypePost['housingarea'][$i];
						$breedinfoModel->breedtype_id = (int)$breedtypePost['breedtype_id'][$i];
						$breedinfoModel->create_at = time();
						$breedinfoModel->update_at = $breedinfoModel->create_at;
						$breedinfoModel->year = User::getYear();
						$breedinfoModel->save();
						Logs::writeLogs('更新养殖种类信息',$breedinfoModel);
					}
				}
				if($loadBreed)
					$breedinfo = Breedinfo::find()->where(['breed_id'=>$loadBreed->id])->all();
				else
					$breedinfo = '';
				return $this->redirect(['/sixcheck/sixcheckindex','farms_id'=>$farms_id]);

			}
		}
		else {
			$model = new Breed();
			if ($model->load(Yii::$app->request->post())) {
				$model->management_area = Farms::getFarmsAreaID($farms_id);
				$model->create_at = time();
				$model->update_at = $model->create_at;
				$model->save();
				Logs::writeLogs('创建养殖信息',$model);
				if ($breedtypePost) {
					//var_dump($parmembers);
					for($i=1;$i<count($breedtypePost['breedtype_id']);$i++) {
						$breedinfoModel = new Breedinfo();
						$breedinfoModel->breed_id = $model->id;
						$breedinfoModel->management_area = Farms::getFarmsAreaID($farms_id);
						$breedinfoModel->number = (int)$breedtypePost['number'][$i];
						$breedinfoModel->basicinvestment = (float)$breedtypePost['basicinvestment'][$i];
						$breedinfoModel->housingarea = (float)$breedtypePost['housingarea'][$i];
						$breedinfoModel->breedtype_id = (int)$breedtypePost['breedtype_id'][$i];
						$breedinfoModel->create_at = time();
						$breedinfoModel->update_at = $breedinfoModel->create_at;
						$breedinfoModel->year = User::getYear();
						$breedinfoModel->save();
						Logs::writeLogs('新增养殖各类信息',$breedinfoModel);
					}
				}
				//return $this->redirect(['breedview', 'id' => $model->id,'farms_id'=>$farms_id]);

				$breedinfo = Breedinfo::find()->where(['breed_id'=>$model->id])->all();

				return $this->redirect(['/sixcheck/sixcheckindex','farms_id'=>$farms_id]);
			}
		}
		return $this->renderAjax('breedcreateajax', [
			'model' => $model,
			'breedtypeFather' => $breedtypeFather,
			'breedinfo' => $breedinfo,
		]);
	}

    public function actionGetbreedtypeson($father_id)
    {
    	$result = Breedtype::find()->where(['father_id'=>$father_id])->all();
    	foreach ($result as $value) {
    		$arraySon[] = [
    			'id'=>$value['id'],
    			'father_id'=>$value['father_id'],
    			'typename'=>$value['typename'],
    		];
    	}
    	echo json_encode(['status'=>1,'breedtypeSon'=>$arraySon]);
    }
    /**
     * Updates an existing Breed model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBreedupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['breedview', 'id' => $model->id]);
        } else {
            return $this->render('breedupdate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Breed model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBreeddelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['breedindex']);
    }

    /**
     * Finds the Breed model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Breed the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Breed::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
