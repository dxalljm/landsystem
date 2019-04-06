<?php
namespace frontend\controllers;
use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\Plant;
use Yii;
use yii\helpers\Url;
use app\models\Insurance;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\plantSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="plant-index">

	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<h3>&nbsp;&nbsp;&nbsp;&nbsp;综合打印</h3></div>
					<div class="box-body">
						
						<?= html::a('
						<div class="col-lg-3 col-md-6 col-sm-6">
							<div class="card card-stats">
								<div class="card-header" data-background-color="orange">
									<i class="fa fa-files-o"></i>
								</div>
								<div class="card-content">
									<p class="category">打印</p>
									<h3 class="card-title">六项基础调查表</h3>
								</div>
								<div class="card-footer">
									<div class="stats">
										<a href="#pablo"></a>
									</div>
								</div>
							</div>
						</div>
						',Url::to(['print/printsixindex']));?>

						<?= html::a('
						<div class="col-lg-3 col-md-6 col-sm-6">
							<div class="card card-stats">
								<div class="card-header" data-background-color="orange">
									<i class="fa fa-files-o"></i>
								</div>
								<div class="card-content">
									<p class="category">打印</p>
									<h3 class="card-title">保险申请书</h3>
								</div>
								<div class="card-footer text-right">
									<div class="stats">
										<a href="#pablo">还有'.Insurance::getNoPrint('left').'条未打印</a>
									</div>
								</div>
							</div>
						</div>
						',Url::to(['insurance/insuranceprintlist']));?>

						<?= html::a('
						<div class="col-lg-3 col-md-6 col-sm-6">
							<div class="card card-stats">
								<div class="card-header" data-background-color="orange">
									<i class="fa fa-files-o"></i>
								</div>
								<div class="card-content">
									<p class="category">打印</p>
									<h3 class="card-title">基础数据核实表</h3>
								</div>
								<div class="card-footer text-right">
									<div class="stats">
										<a href="#pablo">空表</a>
									</div>
								</div>
							</div>
						</div>
						',Url::to(['print/printhsindex']));?>

					</div>
				</div>
			</div>
		</div>
	</section>
</div>
