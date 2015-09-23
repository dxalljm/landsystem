<?php

namespace console\controllers;

use yii\console\Controller;

use console\models\Farms;
use console\models\Parcel;

class TestController extends Controller
{
  public function actionIndex()
  {
    $loadxls = \PHPExcel_IOFactory::load('./1.xls');
    $rows = $loadxls->getActiveSheet()->getHighestRow();
    for($i=1;$i<=$rows;$i++) {
      $id = Farms::find()->where(['farmname'=>$loadxls->getActiveSheet()->getCell('B'.$i)->getValue(),'farmername'=>$loadxls->getActiveSheet()->getCell('C'.$i)->getValue()])->one()['id'];
      $data[$id][] = $loadxls->getActiveSheet()->getCell('D'.$i)->getValue();
    }

    foreach ($data as $key => $value) {
      echo $key.'<br>';
      $model = $this->findModel($key);
      if($model) {
        $model->zongdi = implode('、', $value);
        foreach($value as $val)
          $model->measure += Parcel::find()->where(['unifiedserialnumber'=>$val])->one()['grossarea'];
          $model->save();
      }
    }
  }

  /**
   * Finds the farms model based on its primary key value.
   * If the model is not found, a 404 HTTP exception will be thrown.
   * @param integer $id
   * @return farms the loaded model
   * @throws NotFoundHttpException if the model cannot be found
   */
  protected function findModel($id)
  {
    if (($model = Farms::findOne($id)) !== null) {
      return $model;
    }
    return false;
  }

}
