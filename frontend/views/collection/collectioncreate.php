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

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        缴费业务(<?= Theyear::findOne(1)['years']?>年度)
                    </h3>
                </div>
                <div class="box-body">

    <?= $this->render('collection_form', [
        'model' => $model,
    	'year' => $year,
    	'cardid' => $cardid,
    	'farmsid' => $farmsid,
    	'collectiondataProvider' => $collectiondataProvider,
    	'owe' => $owe,
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
