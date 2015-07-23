<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use app\models\Farms;
use app\models\Farmer;

/* @var $this yii\web\View */
/* @var $model app\models\Collection */

$this->title = 'collection' ;
$title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->title = $title."(".Farms::find()->where(['id'=>$farmsid])->one()['farmname']."—".Farmer::find()->where(['cardid'=>$cardid])->one()['farmername'].")";

?>
<div class="collection-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('collection_form', [
        'model' => $model,
    	'year' => $year,
    	'cardid' => $cardid,
    	'farmsid' => $farmsid,
    	'collectiondataProvider' => $collectiondataProvider,
    	'owe' => $owe,
    ]) ?>

</div>
