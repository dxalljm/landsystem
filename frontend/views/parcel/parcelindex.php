<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use yii\helpers\Url;
use app\models\Farms;
use frontend\helpers\arraySearch;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\parcelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'parcel';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$totalData = clone $dataProvider;
$totalData->pagination = ['pagesize'=>0];
$data = arraySearch::find($totalData)->search();
?>
<section class="content-header">
  <h1>
       <?php //echo Html::a('添加', ['parcelcreate'], ['class' => 'btn btn-success']) ?>
       <?php //echo Html::a('XLS导入', ['parcelxls'], ['class' => 'btn btn-success']) ?>
  </h1>
  <ol class="breadcrumb">

    <li>
        <a href="<?= Url::to('index.php?r=site/index')?>">
            <i class="fa fa-dashboard"></i>
            首页
        </a>
    </li>

    <li>
        <a href="<?= Url::to('index.php?r=parcel/parcelindex')?>">
            <?= $this->title?>
        </a>
    </li>
  </ol>
</section>
<?php $arossareaSum = $data->sum('grossarea');$netareaSum = $data->sum('netarea');$chaSum = $arossareaSum - $netareaSum;?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                <div class="box-body">
                    <?=
                        GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'total' => '<tr>
						        <td></td>
						        <td align="center"><strong>合计</strong></td>
						        <td><strong></strong></td>
						        <td><strong></strong></td>
								
								<td><strong></strong></td>
								<td><strong>'.$netareaSum.'</strong></td>
						        <td><strong>'.$data->count('farms_id',false).'块</strong></td>
						        </tr>',
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                'unifiedserialnumber',
                                'agrotype',
                                'stonecontent',
//                                 'netarea',
                                'figurenumber',
                                [
                                	'format'=>'raw',
                                	'attribute' => 'netarea',
                                	'value' => function ($model) {
                                		if($model->grossarea == $model->netarea) {
                                			return $model->netarea;
                                		} else {
                                			$cha = $model->grossarea - $model->netarea;
                                			return "<font color='red'>".$model->netarea."(".$cha.")</font>";
                                		}
                                }
                                ],
                                [
                                	'attribute' => 'farms_id',
                                	'value' => function ($model) {
                                	if($model->farms_id)
                                		return Farms::find()->where(['id'=>$model->farms_id])->one()['farmname'];
                                	else 
                                		return null;
                                }
                                ],
                                ['class' => 'frontend\helpers\eActionColumn'],
                            ],
                        ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>

