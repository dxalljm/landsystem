<?php

namespace backend\controllers;
use Yii;
use app\models\tables;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Farms;
use app\models\Reviewprocess;
use app\models\Auditprocess;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\ReviewprocessSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'reviewprocess';
$this->title = Tables::find ()->where ( [ 
		'tablename' => $this->title 
] )->one ()['Ctablename'];
$this->params ['breadcrumbs'] [] = $this->title;
?>
<div class="reviewprocess-index">

	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title">
                        <?= $this->title ?>                    </h3>
					</div>
					<div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
						<table class="table table-bordered table-hover">
							<tr height="40px">
								<td align="center">原农场名称</td>
								<td width="13%" align="center">原法人</td>
								<td width="17%" align="center">原面积</td>
								<td width="22%" align="center">现农场名称</td>
								<td width="14%" align="center">现法人</td>
								<td width="14%" align="center">现面积</td>
								<td width="14%" align="center">操作</td>
							</tr>
							<?php foreach ($data as $value) {
							$newfarm = Farms::find()->where(['id'=>$value['newfarms_id']])->one();
							$oldfarm = Farms::find()->where(['id'=>$value['oldfarms_id']])->one();
							if(Reviewprocess::isShowProess($value['actionname'])) {
								?>
							<tr height="40px">
								<td align="center"><?= $oldfarm->farmname?></td>
								<td align="center"><?= $oldfarm->farmername?></td>
								<td align="center"><?= $oldfarm->measure?>亩</td>
								<td align="center"><?= $newfarm->farmname?></td>
								<td align="center"><?= $newfarm->farmername?></td>
								<td align="center"><?= $newfarm->measure?>亩</td>
								<td align="center"><?= html::a('审核',['reviewprocess/reviewprocessinspections','id'=>$value['id']],['class'=>'btn btn-success'])?></td>
							</tr>
							<?php }}?>
						</table>

					</div>
				</div>
			</div>
		</div>
	</section>
</div>
