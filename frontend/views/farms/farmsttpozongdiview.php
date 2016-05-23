<?php
namespace frontend\controllers;
use yii;
use app\models\tables;
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
                    <h3 class="box-title">
                        <?= $this->title ?>
                    </h3>
                </div>
                <div class="box-body">


    
    <table width="100%" border="0">
      <tr>
        <td width="45%">
        <table class="table table-bordered table-hover" height="408px">
		<tr>
			<td width=20% align='right'>农场名称</td>
			<td align='left'><?= $oldFarm->farmname; ?></td>
		</tr>
		<tr>
			<td width=20% align='right'>承包人姓名</td>
			<td align='left'><?= $oldFarm->farmername?></td>
		</tr>
		<tr>
			<td width=20% align='right'>身份证号</td>
			<td align='left'><?= $oldFarm->cardid?></td>
		</tr>
			<tr>
			<td width=20% align='right'>电话号码</td>
			<td align='left'><?= $oldFarm->telephone ?></td>
		</tr>
			<tr>
			<td width=20% align='right'>农场位置</td>
			<td align='left'><?= $oldFarm->address?></td>
		</tr>
		<tr>
			<td width=20% align='right'>管理区</td>
			<td align='left'><?= ManagementArea::findOne($oldFarm->management_area)['areaname'] ?></td>
		</tr>
		<tr>
			<td width=20% align='right'>合同号</td>
			<td align='left'><?= $oldFarm->contractnumber ?></td>
		</tr>
		<tr>
			<td width=20% align='right'>合同面积</td>
			<td align='left'><?= $oldFarm->contractarea?>亩</td>
		</tr>
		<tr>
			<td width=20% align='right'>宗地</td>
			<td align='left'> <?= $oldFarm->zongdi?> </td>
		</tr>
		<tr>
		  <td width=20% align='right'>宗地面积</td>
		  <td align='left'><?= $oldFarm->measure ?></td>
		  </tr>
		
		<tr>
			<td width=20% align='right'>未明确地块面积</td>
			<td align='left'><?= $oldFarm->notclear?>亩</td>
		</tr>
		<tr>
			<td width=20% align='right'>未明确状态面积</td>
			<td align='left'><?= $oldFarm->notstate?>亩</td>
		</tr>
		
		<tr>
			<td width=20% align='right'><font color="red">减少宗地</font></td>
			<td align='left'> <font color="red"><?= $ttpoModel->ttpozongdi?></font> </td>
		</tr>
		<tr>
			<td width=20% align='right'><font color="red">减少面积</font></td>
			<td align='left'> <font color="red"><?= $ttpoModel->ttpoarea?></font> </td>
		</tr>
		<tr>
			<td width=20% align='right'>转让日期</td>
			<td align='left'> <?= date('Y年m月d日',$ttpoModel->create_at)?> </td>
		</tr>
	</table></td>
        <td><font size="5"><i class="fa fa-arrow-right"></i></font></td>
        <td>
        <table class="table table-bordered table-hover" height="408px">
          <tr>
            <td width="20%" align='right'>农场名称</td>
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
          <tr>
            <td width="15%" align='right'>农场位置</td>
            <td align='left'><?= $newFarm->address?></td>
          </tr>
          <tr>
            <td width="15%" align='right'>管理区</td>
            <td align='left'><?= ManagementArea::findOne($newFarm->management_area)['areaname'] ?></td>
          </tr>
          <tr>
            <td width="15%" align='right'>合同号</td>
            <td align='left'><?= $newFarm->contractnumber ?></td>
          </tr>
          <tr>
            <td width="15%" align='right'>合同面积</td>
            <td align='left'><?= $newFarm->contractarea ?></td>
          </tr>
          <tr>
            <td width="15%" align='right'>宗地</td>
            <td align='left'><?= $newFarm->zongdi?></td>
          </tr>
           <tr>
            <td width="15%" align='right'>宗地面积</td>
            <td align='left'><?= $newFarm->measure ?></td>
          </tr>
          <tr>
            <td width="15%" align='right'>未明确地块面积</td>
            <td align='left'><?= $newFarm->notclear?>亩</td>
          </tr>
          <tr>
            <td width="15%" align='right'>未明确状态面积</td>
            <td align='left'><?= $newFarm->notstate?>亩</td>
          </tr>
          <tr>
			<td width=20% align='right'><font color="red">增加宗地</font></td>
			<td align='left'> <font color="red"><?= $ttpoModel->ttpozongdi?> </font></td>
		</tr>
		<tr>
			<td width=20% align='right'><font color="red">增加面积</font></td>
			<td align='left'> <font color="red"><?= $ttpoModel->ttpoarea?> </font></td>
		</tr>
		<tr>
			<td width=20% align='right'>转让日期</td>
			<td align='left'> <?= date('Y年m月d日',$ttpoModel->create_at)?> </td>
		</tr>
        </table></td>
      </tr>
    </table>
    <?php if($state == 0) {?>
    <?= Html::a('提交申请', ['ttpozongdi/ttpozongdiupdate','id'=>$ttpoModel->id], ['class' => 'btn btn-success','data' => ['confirm' => '您确定要提交申请吗？', 'method' => 'post']])?>
    <?= Html::a('修改', ['farms/farmsttpoupdate','id'=>$ttpoModel->id], ['class' => 'btn btn-success'])?>
    <?= Html::a('撤消申请', ['ttpozongdi/ttpozongdidelete','id'=>$ttpoModel->id], ['class' => 'btn btn-success','data' => ['confirm' => '您确定要撤消申请吗？', 'method' => 'post']])?>
    
    <?php } else 
    	echo Html::a('打印', ['reviewprocess/reviewprocessfarmssplit','oldfarmsid'=>$oldFarm->id,'newfarmsid'=>$newFarm->id,'reviewprocessid'=>$ttpoModel['reviewprocess_id']], ['class' => 'btn btn-success']);
    ?>

    <?= Html::a('返回', [Yii::$app->controller->id.'ttpomenu','farms_id'=>$farms_id], ['class' => 'btn btn-success'])?>
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
