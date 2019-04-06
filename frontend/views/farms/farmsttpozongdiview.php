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
use app\models\Farms;
use app\models\Lease;
use app\models\Ttpozongdi;
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


    <?php
    if(empty($ttpoModel->ttpozongdi)) {
        $ttpozongdiArray = [];
    } else {
        $ttpozongdiArray = explode('、',$ttpoModel->ttpozongdi);
    }

    ?>
    <table width="100%" border="0">
      <tr>
        <td width="45%">
        <table class="table table-bordered table-hover" height="408px">
		<tr>
			<td width=30% align='right'>农场名称</td>
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
            <?php if($ttpoModel->oldnewfarms_id) {?>
		<tr>
            <td width=20% align='right'>原合同号</td>
			<td align='left'><?= $ttpoModel->oldcontractnumber ?></td>
		</tr>
                <?php
                if($ttpoModel->actionname !== 'farmssplitcontinue') {
                    ?>
                    <tr>
                        <td width=20% align='right'>新合同号</td>
                        <td align='left'><?php if (Farms::getContractnumberArea($ttpoModel->oldchangecontractnumber) <= 0) echo '<s>' . $ttpoModel->oldchangecontractnumber . '</s>'; else echo $ttpoModel->oldchangecontractnumber; ?></td>
                    </tr>
                    <?php
                }
                    ?>
            <?php } else {?>
                <tr>
                    <td width=20% align='right'>合同号</td>
                    <td align='left'><s><?= $ttpoModel->oldcontractnumber ?></s></td>
                </tr>
            <?php }?>
            <?php if($ttpoModel->oldnewfarms_id) {?>
		<tr>
			<td width=20% align='right'>原合同面积</td>
			<td align='left'><?= Farms::getContractnumberArea($ttpoModel->oldcontractnumber)?>亩</td>
		</tr>
            <?php
            if($ttpoModel->actionname !== 'farmssplitcontinue') {
                ?>
                <tr>
                    <td width=20% align='right'>新合同面积</td>
                    <td align='left'><?= Farms::getContractnumberArea($ttpoModel->oldchangecontractnumber) ?>亩</td>
                </tr>
                <?php
            }
                ?>
            <?php } else {?>
                <tr>
                    <td width=20% align='right'>合同面积</td>
                    <td align='left'><s><?= Farms::getContractnumberArea($ttpoModel->oldcontractnumber)?>亩</s></td>
                </tr>
            <?php }?>
		<tr>
			<td width=20% align='right'>宗地</td>
			<td align='left'>
                <?php if($ttpoModel->oldzongdi) $zongdiArray = explode('、',$ttpoModel->oldzongdi); else $zongdiArray = [];?>
                <table width="100%" border="0" align="center">
                    <?php

                    if($ttpoModel->oldzongdi) {
                    $arrayZongdi = explode('、', $ttpoModel->oldzongdi);
                    $ttpozongdi = explode('、', $ttpoModel->ttpozongdi);
                    foreach ($arrayZongdi as $key => $zongdi) {
                        foreach ($ttpozongdi as $tkey => $tzongdi) {
                            if(Lease::getZongdi($zongdi) == Lease::getZongdi($tzongdi)) {
                                $cha = Lease::getArea($zongdi) - Lease::getArea($tzongdi);

                                if(intval($cha) > 0)
                                    $arrayZongdi[$key] = Lease::getZongdi($zongdi)."(".$cha.")";
                            }
                        }
                    }} else
                        $arrayZongdi = [];

                    if($arrayZongdi) {
                    for($i = 0;$i<count($arrayZongdi);$i++) {
                        if ($i % 4 == 0) {
                            echo '<tr height="10">';
                            echo '<td align="left">';
                            if (strstr($ttpoModel->ttpozongdi, Lease::getZongdi($arrayZongdi[$i]))) {
                                echo '<span class="italic text-red">' . $arrayZongdi[$i] . '<small class="fa fa-minus pull-center text-sm text-red"></small></span>';
                            } else
                                echo '<span class="italic">' . $arrayZongdi[$i] . '</span>';
                            echo '</td>';
                        } else {
                            echo '<td align="left">';
                            if (strstr($ttpoModel->ttpozongdi, Lease::getZongdi($arrayZongdi[$i]))) {
                                echo '<span class="italic text-red">' . $arrayZongdi[$i] . '<small class="fa fa-minus pull-center text-sm text-red"></small></span>';
                            } else
                                echo '<span class="italic">' . $arrayZongdi[$i] . '</span>';
                            echo '</td>';
                        }
                    }
                    }?>
                </table>
            </td>
		</tr>
		<tr>
		  <td width=20% align='right'>宗地面积</td>
		  <td align='left'><?= $ttpoModel->oldchangemeasure ?></td>
		  </tr>
		
		<tr>
			<td width=20% align='right'>未明确地块面积</td>
			<td align='left'><?= $ttpoModel->oldnotclear?>亩</td>
		</tr>
		<tr>
			<td width=20% align='right'>未明确状态面积</td>
			<td align='left'><?= $oldFarm->notstate?>亩</td>
		</tr>
		<?php if($ttpoModel->actionname !== 'farmstransfer' and $ttpoModel->actionname !== 'farmstransfermergecontract') {?>
		<tr>
			<td width=20% align='right'><font color="red">减少宗地（<?= count($ttpozongdiArray)?>宗）</font></td>
			<td align='left'><?php 
			
			if($ttpozongdiArray) {
				echo '<table width="100%" border="0" align="right">';
				for($i = 0;$i<count($ttpozongdiArray);$i++) {
					if($i%4 == 0) {
						echo '<tr height="10">';
						echo  '<td align="left">';
						echo  '<font color="red">'.$ttpozongdiArray[$i].'</font>';
						echo  '</td>';
					} else {
						echo '<td align="left">';
						echo  '<font color="red">'.$ttpozongdiArray[$i].'</font>';
						echo '</td>';
					}
					//				if($i%10 == 0)
						//					echo '</tr>';
				}
				echo '</table>';
			}
			?></font> </td>
		</tr>
		<tr>
			<td width=20% align='right'><font color="red">减少面积</font></td>
			<td align='left'> <font color="red"><?php if($ttpoModel->actionname == 'farmstransfer' or $ttpoModel->actionname == 'farmstransfermergecontract') echo Farms::getContractnumberArea($ttpoModel->oldcontractnumber); else echo $ttpoModel->ttpoarea?></font> </td>
		</tr>
		<?php }?>
		<tr>
			<td width=20% align='right'>转让日期</td>
			<td align='left'> <?= date('Y年m月d日',$ttpoModel->create_at)?> </td>
		</tr>
	</table></td>
        <td><font size="5"><i class="fa fa-arrow-right"></i></font></td>
        <td>
        <table class="table table-bordered table-hover" height="408px">
          <tr>
            <td width="30%" align='right'>农场名称</td>
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
            <?php if($ttpoModel->newnewfarms_id) {?>
          <tr>
            <td width="15%" align='right'>原合同号</td>
            <td align='left'><?php if($ttpoModel->newnewfarms_id) echo $ttpoModel->newcontractnumber ?></td>
          </tr>
                <tr>
                    <td width="15%" align='right'>新合同号</td>
                    <td align='left'><?= $ttpoModel->newchangecontractnumber ?></td>
                </tr>
            <?php } else {?>
            <tr>
                <td width="15%" align='right'>合同号</td>
                <td align='left'><?= $ttpoModel->newchangecontractnumber ?></td>
            </tr>
            <?php }?>
            <?php if($ttpoModel->newnewfarms_id) {?>
          <tr>
            <td width="15%" align='right'>原合同面积</td>
            <td align='left'><?php if($ttpoModel->newnewfarms_id) echo Farms::getContractnumberArea($ttpoModel->newcontractnumber) ?></td>
          </tr>
                <tr>
                    <td width="15%" align='right'>新合同面积</td>
                    <td align='left'><?= $newFarm->contractarea ?></td>
                </tr>
            <?php } else {?>
            <tr>
                <td width="15%" align='right'>合同面积</td>
                <td align='left'><?= $newFarm->contractarea ?></td>
            </tr>
            <?php }?>
          <tr>
            <td width="15%" align='right'>宗地</td>
            <td align='left'>
                <table width="100%" border="0" align="center">
                    <?php if(!empty($ttpoModel['newchangezongdi'])) $zongdiArray = explode('、', $ttpoModel->newchangezongdi); else $zongdiArray = [];?>
                    <?php
                    for($i = 0;$i<count($zongdiArray);$i++) {
                        if($i%4 == 0) {
                            echo '<tr height="10">';
                            echo '<td align="left">';
                            if(strstr($ttpoModel->ttpozongdi,$zongdiArray[$i])) {
                                echo '<span class="italic text-red">'.$zongdiArray[$i].'<small class="fa fa-plus pull-center text-sm text-red"></small></span>';
                            } else
                                echo '<span class="italic">'.$zongdiArray[$i].'</span>';
                            echo '</td>';
                        } else {
                            echo '<td align="left">';
                            if(strstr($ttpoModel->ttpozongdi,$zongdiArray[$i])) {
                                echo '<span class="italic text-red">'.$zongdiArray[$i].'</span><small class="fa fa-plus pull-center text-sm text-red"></small>';
                            } else
                                echo '<span class="italic">'.$zongdiArray[$i].'</span>';
                            echo '</td>';
                        }

                    }?>
                </table>
                </td>
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
            <td align='left'><?php if($newFarm->notstate == '') echo 0; else echo $newFarm->notstate?>亩</td>
          </tr>
          <?php 
          
          if($ttpoModel->actionname == 'farmstransfer' or $ttpoModel->actionname == 'farmstransfermergecontract') {	
          	?>
          <tr>
			<td width=20% align='right'><font color="red">确认宗地（<?= count($ttpozongdiArray)?>宗）</font></td>
			<td align='left'> <font color="red"><?php 
			
			if($ttpozongdiArray) {
				echo '<table width="100%" border="0" align="right">';
				for($i = 0;$i<count($ttpozongdiArray);$i++) {
					if($i%4 == 0) {
						echo '<tr height="10">';
						echo  '<td align="left">';
						echo  '<font color="red">'.$ttpozongdiArray[$i].'</font>';
						echo  '</td>';
					} else {
						echo '<td align="left">';
						echo  '<font color="red">'.$ttpozongdiArray[$i].'</font>';
						echo '</td>';
					}
					//				if($i%10 == 0)
						//					echo '</tr>';
				}
				echo '</table>';
			}
			?> </font></td>
		</tr>
		<tr>
			<td width=20% align='right'><font color="red">增加面积</font></td>
			<td align='left'> <font color="red"><?= $ttpoModel->ttpoarea?> </font></td>
		</tr>
          <?php } else {
         
          ?>
          <tr>
			<td width=20% align='right'><font color="red">增加宗地（<?= count($ttpozongdiArray)?>宗）</font></td>
			<td align='left'>
			<?php 
			
			if($ttpozongdiArray) {
				echo '<table width="100%" border="0" align="right">';
				for($i = 0;$i<count($ttpozongdiArray);$i++) {
					if($i%4 == 0) {
						echo '<tr height="10">';
						echo  '<td align="left">';
						echo  '<font color="red">'.$ttpozongdiArray[$i].'</font>';
						echo  '</td>';
					} else {
						echo '<td align="left">';
						echo  '<font color="red">'.$ttpozongdiArray[$i].'</font>';
						echo '</td>';
					}
					//				if($i%10 == 0)
						//					echo '</tr>';
				}
				echo '</table>';
			}
			?>
			</td>
		</tr>
		<tr>
			<td width=20% align='right'><font color="red">增加面积</font></td>
			<td align='left'> <font color="red"><?php if($ttpoModel->actionname == 'farmstransfer' or $ttpoModel->actionname == 'farmstransfermergecontract') echo Farms::getContractnumberArea($ttpoModel->oldcontractnumber);else echo $ttpoModel->ttpoarea?> </font></td>
		</tr>
		<?php }?>
		<tr>
			<td width=20% align='right'>转让日期</td>
			<td align='left'> <?= date('Y年m月d日',$ttpoModel->create_at)?> </td>
		</tr>
        </table></td>
      </tr>
    </table>
<!--    --><?php //var_dump(Ttpozongdi::getSamefarmsidCount($ttpoModel->id));?>
    <?php if($state == 0) {?>
    <?php if(Ttpozongdi::getSamefarmsidCount($ttpoModel->id) > 1)
            echo Html::a('全部提交', ['ttpozongdi/ttpozongdiall','samefarms_id'=>$ttpoModel->samefarms_id], ['class' => 'btn btn-success','method' => 'post']);
        else echo Html::a('提交申请', ['ttpozongdi/ttpozongdiupdate','id'=>$ttpoModel->id], ['class' => 'btn btn-success','data' => ['confirm' => '您确定要提交申请吗？', 'method' => 'post']]);?>
    <?= Html::a('修改', ['farms/farmsttpoupdate'.$ttpoModel->actionname,'id'=>$ttpoModel->id], ['class' => 'btn btn-success'])?>
    <?= Html::a('撤消申请', ['ttpozongdi/ttpozongdidelete','id'=>$ttpoModel->id], ['class' => 'btn btn-success','data' => ['confirm' => '您确定要撤消申请吗？', 'method' => 'post']])?>
    <?php if(Farms::getContractnumberArea($ttpoModel->oldchangecontractnumber) > 0 and ($ttpoModel->actionname == 'farmssplit' or $ttpoModel->actionname == 'farmstozongdi' or $ttpoModel->actionname == 'farmssplitcontinue'))
            echo Html::a('继续新建', ['farms/farmssplitcontinue','samefarms_id'=>$ttpoModel->samefarms_id,'farms_id'=>$ttpoModel->oldnewfarms_id,'oldfarms_id'=>$ttpoModel->oldfarms_id,'ttpozongdi_id'=>$ttpoModel->id], ['class' => 'btn btn-success','method' => 'post']);
    		echo '&nbsp;';
    		echo Html::a('继续合并', ['farms/farmsttpozongdi','farms_id'=>$ttpoModel->oldfarms_id], ['class' => 'btn btn-success','method' => 'post']);
        ?>
    <?php }?>

    <?= Html::a('返回', [Yii::$app->controller->id.'menu','farms_id'=>$ttpoModel->newnewfarms_id], ['class' => 'btn btn-success'])?>
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
