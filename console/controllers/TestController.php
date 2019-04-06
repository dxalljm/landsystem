<?php

namespace console\controllers;

use yii\console\Controller;


class TestController extends Controller
{

  public function actionTest($str = NULL)
  {
  	echo 'hello'.$str;
  }

}
