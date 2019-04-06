<?php
namespace frontend\controllers;use app\models\User;
use Yii;
use yii\helpers\Html;
use app\models\Machine;
use frontend\helpers\ActiveFormrdiv;
use app\models\Tables;
use app\models\Tablefields;
use app\models\Reviewprocess;
use app\models\Nation;
use app\models\Farmer;
/* @var $this yii\web\View */
/* @var $model app\models\Reviewprocess */

?>
<object  id="LODOP_OB" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width=0 height=0> 
       <embed id="LODOP_EM" type="application/x-print-lodop" width=0 height=0></embed>
</object>
<div class="reviewprocess-form">


    <?php $form = ActiveFormrdiv::begin(); ?>
    
    
    <section class="content">
    <div class="row">
        <div class="col-xs-12">
         <div class="form-group">
    	   	&nbsp;&nbsp;<?= Html::a('返回', Yii::$app->getRequest()->getReferrer(), ['class' => 'btn btn-success'])?>
    	</div>
<!--             <div class="box"> -->

<!--                 <div class="box-body"> -->
    <div class="col-md-6" id="ttpoprint">
<style type="text/css">
.ttpoprint {
	font-family: "仿宋";
	font-size:20px;
}
.tablehead{ width:100%; height:30px; line-height:20px; text-align:center; float:left; font-size:30px; font-family:"黑体"}
</style>    

          <!-- Box Comment -->
          <div class="box box-widget">
            <div class="box-header with-border">
              <div class="user-block">
                <span class="tablehead">农场档案</span>
              </div>
              <!-- /.user-block -->
             
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
             <table width="100%" border="1" cellpadding="0" cellspacing="0" class="ttpoprint">
			  <tr height="40px">
			    <td width="18%" align="center">农场名称</td>
			    <td colspan="3" align="center"><?= $farm->farmname?></td>
			    <td width="23%" rowspan="5" align="center"><?php if($farmer) echo '&nbsp;'.Html::img($farmer->photo,['height'=>'130px']);?></td>
			  </tr>
			  <tr height="40px">
			    <td align="center">法人姓名</td>
			    <td width="20%" align="center"><?= $farm->farmername?></td>
			    <td width="20%" align="center">曾用名</td>
			    <td width="19%" align="center"><?php if($farmer) echo $farmer->farmerbeforename?></td>
			    </tr>
			  <tr height="40px">
			    <td align="center">民族</td>
			    <td align="center"><?php if($farmer) echo Nation::find()->where(['id'=>$farmer->nation])->one()['nationname']?></td>
			    <td align="center">政治面貌</td>
			    <td align="center"><?php if($farmer) echo $farmer->political_outlook?></td>
			    </tr>
			  <tr height="40px">
			    <td align="center">文化程序</td>
			    <td align="center"><?php if($farmer) echo $farmer->cultural_degree?></td>
			    <td align="center">承包面积</td>
			    <td align="center"><?= $farm->contractarea?></td>
			    </tr>
			  <tr height="40px">
			    <td align="center">身份证号码</td>
			    <td align="center"><?= $farm->cardid?></td>
			    <td align="center">联系电话</td>
			    <td align="center"><?= $farm->telephone?></td>
			    </tr>
			  <tr height="40px">
			    <td align="center">合同号</td>
			    <td align="center"><?= $farm->contractnumber?></td>
			    <td align="center">地理坐标</td>
			    <td colspan="2" align="center"><?= $farm->longitude?>
			      
			      <?= $farm->latitude?></td>
			    </tr>
			  <tr>
			    <td align="center">农场位置</td>
			    <td colspan="4" align="left"><?= $farm->address?></td>
			    </tr>
			  <tr>
			 
			    <td align="center">宗地</td>
			    <td colspan="4" align="left"><table width="100%" border="0" align="right">
			    <?php if(!empty($farm->zongdi)) $zongdiArray = explode('、', $farm->zongdi); else $zongdiArray = [];?>
			    <?php for($i = 0;$i<count($zongdiArray);$i++) {
			    	
			    	if($i%5 == 0) {
			    		echo '<tr>';
			    		echo '<td>';
			    		echo $zongdiArray[$i];
			    		echo '</td>';
			    	} else {
			    		echo '<td>';
			    		echo $zongdiArray[$i];
			    		echo '</td>';
			    	}
			    	
			    }?></table><br></td>
			    </tr>
			  <tr>
			    <td align="center"  valign="middle">户籍所在地</td>
			    <td colspan="4" align="left"><?php if($farmer) echo $farmer->domicile?></td>
			    </tr>
			  <tr>
			    <td align="center">现住地<</td>
			    <td colspan="4" align="left"  valign="middle"><?php if($farmer) echo $farmer->nowlive?>
			    </tr>
			  <tr>
			    <td colspan="5" align="center"><br>家庭主要成员 <br><br></td>
			    </tr>
			    <tr>
			    <td align="center"><br>关系<br><br></td>
			    <td align="center"><br>姓名 <br><br> </td>
			    <td colspan="2" align="center"><br>身份证号码<br><br> </td>
			    <td align="center"><br>备注<br><br> </td>
			    </tr>
			    <?php if($members) {?>
			    <?php foreach ($members as $member) {?>
			  	<tr>
			  	  <td align="center"><br><?= Farmer::getRelationship($member['relationship'])?><br><br></td>
			  	  <td align="center"><br><?= $member['membername']?><br><br></td>
			  	  <td colspan="2" align="center"><br><?= $member['cardid']?><br><br></td>
			  	  <td align="center"><br><?= $member['remarks']?><br><br></td>
		  	    </tr>
			  	<?php }}?>
			</table>
			<?php if($machine) {?>
 <table width="100%" border="1" cellpadding="0" cellspacing="0" class="ttpoprint">
  <tr>
    <td colspan="5" align="center">生产及交通工具</td>
    </tr>
  <tr>
    <td align="center">名称</td>
    <td align="center">数量</td>
    <td align="center">动力</td>
    <td align="center">购置年限</td>
    <td align="center">备注</td>
  </tr>
  <?php

  foreach ($machine as $value) {?>
  <tr>
    <td align="center"><?= $value['machinename']?></td>
    <td align="center">1</td>
    <td align="center"><?= Machine::find()->where(['id'=>$value['machine_id']])->one()['filename']?></td>
    <td align="center"></td>
    <td align="center"></td>
  </tr>
  <?php }?>
</table>
<?php }?>
			<br>
			
            </div>
            <!-- /.box-body -->
           
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->

    

    <?php ActiveFormrdiv::end(); ?>
<!--                 </div> -->
<!--             </div> -->
        </div>
    </div>
</section>
</div>
