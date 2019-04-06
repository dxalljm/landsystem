<?php
/* @var $this yii\web\View */
use app\models\ManagementArea;
use yii\helpers\Html;
use dosamigos\datetimepicker\DateTimePicker;
use app\models\Farms;
use app\models\Farmer;
use app\models\Farmerinfo;
use yii\helpers\Url;

use app\models\User;
?>

<div class="farms-menu">

	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<h3>
                        业务办理<font color="red">(<?= User::getYear()?>年度)</font>&nbsp;&nbsp;
							<?php if(User::getItemname('主任') or User::getItemname('地产科') or User::getItemname('法规科')) {?>
								<?= html::a('农场档案打印',Url::to(['print/printfarmsfile','farms_id'=>$farm->id]),['class'=>'btn btn-primary'])?>&nbsp;&nbsp;
                        	<?= html::a('承包合同打印',Url::to(['print/printcontract','farms_id'=>$farm->id]),['class'=>'btn btn-primary'])?>&nbsp;&nbsp;
							<?= Html::button('图库',['onclick'=>"javascript:window.open('".yii::$app->urlManager->createUrl(['picturelibrary/showimg','farms_id'=>$_GET['farms_id']])."','','width=1200,height=800,top=250,left=380, location=no, toolbar=no, status=no, menubar=no, resizable=no, scrollbars=yes');return false;",'class'=>'btn btn-success']);?>
							<?php }?>
                    </h3>

					</div>
					<div class="box-body">
						<?= Farms::showFarminfo($_GET['farms_id'])?>
						<br>
<?= $farmsmenu?>
<?php //\app\models\Reviewprocess::getReviewFarms();?>
<?php Farms::showRow($_GET['farms_id']);?>

					</div>
				</div>
			</div>
		</div>
	</section>

</div>

