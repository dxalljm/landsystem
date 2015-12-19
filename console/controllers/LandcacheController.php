<?php

namespace console\controllers;

use yii\console\Controller;
use app\models\Farms;

class LandcacheController extends Controller
{

  public function actionIndex($str = NULL)
  {
  		Farms::getFarmarea();
  }

}
