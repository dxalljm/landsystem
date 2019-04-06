<?php
namespace frontend\controllers;use app\models\User;
use yii;
use app\models\Tables;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\ManagementArea;
use app\models\Cooperative;
use frontend\helpers\grid\GridView; 
use yii\web\View;
use app\models\Cooperativetype;
use app\models\Parcel;
use app\models\CooperativeOfFarm;
/* @var $this yii\web\View */
/* @var $oldFarm app\models\farms */

$this->title = 'ID:'.$ttpoModel->id;
$title = Tables::find()->where(['tablename'=>'ttpo'])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['farmsindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="farms-view">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                <div class="box-body">


    
    <table width="100%" border="0">
      <tr>
        <td><table class="table table-bordered table-hover">
		<tr>
			<td width=20% align='right'>农场名称</td>
			<td align='left'><?= $oldFarm->farmname; ?></td>
		</tr>
		<tr>
			<td width=15% align='right'>承包人姓名</td>
			<td align='left'><?= $oldFarm->farmername?></td>
		</tr>
		<tr>
			<td width=15% align='right'>身份证号</td>
			<td align='left'><?= $oldFarm->cardid?></td>
		</tr>
			<tr>
			<td width=15% align='right'>电话号码</td>
			<td align='left'><?= $oldFarm->telephone ?></td>
		</tr>
		<tr>
		<tr>
			<td width=15% align='right'>农场位置</td>
			<td align='left'><?= $oldFarm->address?></td>
		</tr>
		<tr>
			<td width=15% align='right'>管理区</td>
			<td align='left'><?= ManagementArea::findOne($oldFarm->management_area)['areaname'] ?></td>
		</tr>
		<tr>
			<td width=15% align='right'>审批年度</td>
			<td align='left'><?= $oldFarm->spyear ?></td>
		</tr>
		<tr>
			<td width=15% align='right'>面积</td>
			<td align='left'><?= $oldFarm->measure ?></td>
		</tr>
		<tr>
			<td width=15% align='right'>宗地</td>
			<td align='left'> 
			<?= $oldFarm->zongdi?>
      </td>
		</tr>
		<tr>
			<td width=15% align='right'>未明确地块</td>
			<td align='left'><?= $oldFarm->notclear?>亩</td>
		</tr>
		<tr>
			<td width=15% align='right'>合作社</td>
			
				
			<td align='left' >
			<?php $cooperativeoffarm = CooperativeOfFarm::find()->where(['farms_id'=>$oldFarm->id])->all();?>
			<?php if($cooperativeoffarm) {?>
			
			<div >
			    <div class="panel-heading">
			      <h4 class="panel-title">
			        <a data-toggle="collapse" data-parent="#accordion" 
			          href="#collapseOne">
			           <?php foreach($cooperativeoffarm as $cooper) {
						echo Cooperative::findOne($cooper['cooperative_id'])['cooperativename'].'&nbsp;&nbsp;&nbsp;&nbsp;';}?>
			          
			        </a>
			      </h4>
			    </div>
			    <div id="collapseOne" class="panel-collapse collapse">
			      <div class="panel-body">
			     
			        <table class="table table-striped table-bordered table-hover table-condensed">
				<tr>
					<td align='center'>合作社名称</td>
					<td align='center'>合作社类型</td>
					<td align='center'>理事长姓名</td>
					<td align='center'>入社人数</td>
				</tr>
				 <?php foreach($cooperativeoffarm as $cooper) {
					$cooperative = Cooperative::findOne($cooper['cooperative_id']);?>
				<tr>
					<td align='center'><?php if($cooperative !== NULL) echo $cooperative->cooperativename ?></td>
					<td align='center'><?php if($cooperative !== NULL) echo Cooperativetype::findOne($cooperative->cooperativetype)['typename'] ?></td>
					<td align='center'><?php if($cooperative !== NULL) echo $cooperative->directorname ?></td>
					<td align='center'><?php if($cooperative !== NULL) echo $cooperative->peoples ?></td>
				</tr><?php }?>
			</table>
			      </div>
			    </div>
			  </div><?php }?></td>
		</tr>
		<tr>
			<td width=15% align='right'>调查日期</td>
			<td align='left'><?= $oldFarm->surveydate ?></td>
		</tr>
		<tr>
			<td width=15% align='right'>地产科签字</td>
			<td align='left'><?= $oldFarm->groundsign ?></td>
		</tr>
		<tr>
			<td width=15% align='right'>地星调查员</td>
			<td align='left'><?= $oldFarm->investigator ?></td>
		</tr>
		<tr>
			<td width=15% align='right'>法人签字</td>
			<td align='left'><?= $oldFarm->farmersign ?></td>
		</tr>
	</table></td>
        <td><font size="5"><i class="fa fa-arrow-right"></i></font></td>
        <td><table class="table table-bordered table-hover">
          <tr>
            <td width="15%" align='right'>农场名称</td>
            <td align='left'><?= $newFarm->farmname; ?></td>
          </tr>
          <tr>
            <td width="15%" align='right'>承包人姓名</td>
            <td align='left'><?= $newFarm->farmername?></td>
          </tr>
          <tr>
            <td width="15%" align='right'>身份证号</td>
            <td align='left'><?= $newFarm->cardid?></td>
          </tr>
          <tr>
            <td width="15%" align='right'>电话号码</td>
            <td align='left'><?= $newFarm->telephone ?></td>
          </tr>
          <tr></tr>
          <tr>
            <td width="15%" align='right'>农场位置</td>
            <td align='left'><?= $newFarm->address?></td>
          </tr>
          <tr>
            <td width="15%" align='right'>管理区</td>
            <td align='left'><?= ManagementArea::findOne($newFarm->management_area)['areaname'] ?></td>
          </tr>
          <tr>
            <td width="15%" align='right'>审批年度</td>
            <td align='left'><?= $newFarm->spyear ?></td>
          </tr>
          <tr>
            <td width="15%" align='right'>面积</td>
            <td align='left'><?= $newFarm->measure ?></td>
          </tr>
          <tr>
            <td width="15%" align='right'>宗地</td>
            <td align='left'><?= $newFarm->zongdi?></td>
          </tr>
          <tr>
            <td width="15%" align='right'>未明确地块</td>
            <td align='left'><?= $newFarm->notclear?>
              亩</td>
          </tr>
          <tr>
            <td width="15%" align='right'>合作社</td>
            <td align='left' ><?php $cooperativeoffarm = CooperativeOfFarm::find()->where(['farms_id'=>$newFarm->id])->all();?>
              <?php if($cooperativeoffarm) {?>
              <div >
                <div class="panel-heading">
                  <h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" 
			          href="#collapseOne">
                    <?php foreach($cooperativeoffarm as $cooper) {
						echo Cooperative::findOne($cooper['cooperative_id'])['cooperativename'].'&nbsp;&nbsp;&nbsp;&nbsp;';}?>
                  </a> </h4>
                </div>
                <div id="collapseOne2" class="panel-collapse collapse">
                  <div class="panel-body">
                    <table class="table table-striped table-bordered table-hover table-condensed">
                      <tr>
                        <td align='center'>合作社名称</td>
                        <td align='center'>合作社类型</td>
                        <td align='center'>理事长姓名</td>
                        <td align='center'>入社人数</td>
                      </tr>
                      <?php foreach($cooperativeoffarm as $cooper) {
					$cooperative = Cooperative::findOne($cooper['cooperative_id']);?>
                      <tr>
                        <td align='center'><?php if($cooperative !== NULL) echo $cooperative->cooperativename ?></td>
                        <td align='center'><?php if($cooperative !== NULL) echo Cooperativetype::findOne($cooperative->cooperativetype)['typename'] ?></td>
                        <td align='center'><?php if($cooperative !== NULL) echo $cooperative->directorname ?></td>
                        <td align='center'><?php if($cooperative !== NULL) echo $cooperative->peoples ?></td>
                      </tr>
                      <?php }?>
                    </table>
                  </div>
                </div>
              </div>
              <?php }?></td>
          </tr>
          <tr>
            <td width="15%" align='right'>调查日期</td>
            <td align='left'><?= $newFarm->surveydate ?></td>
          </tr>
          <tr>
            <td width="15%" align='right'>地产科签字</td>
            <td align='left'><?= $newFarm->groundsign ?></td>
          </tr>
          <tr>
            <td width="15%" align='right'>法人签字</td>
            <td align='left'><?= $newFarm->farmersign ?></td>
          </tr>
        </table></td>
      </tr>
    </table>
    <?= Html::a('返回', [Yii::$app->controller->id.'ttpomenu','farms_id'=>$oldFarm->id], ['class' => 'btn btn-success'])?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
<?php $this->registerJsFile('js/vendor/bower/jquery/dist/jquery.min.js', ['position' => View::POS_HEAD]); ?>

   <script>$(function () 
      { $("[data-toggle='popover']").popover();
      });
   </script>
