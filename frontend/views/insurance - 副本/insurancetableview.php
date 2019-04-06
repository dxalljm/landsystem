<?php
namespace frontend\controllers;use app\models\User;
use app\models\Insurancecompany;
use app\models\Insurancedck;
use frontend\helpers\datetozhongwen;
use yii\helpers\Html;
use app\models\Tables;
use app\models\Tablefields;
use app\models\Farms;
use app\models\User;
use app\models\ManagementArea;
/* @var $this yii\web\View */
/* @var $model app\models\Reviewprocess */

?>

<div class="reviewprocess-form">
    
    <section class="content">
    <div class="row">
        <div class="col-xs-12">
<!--             <div class="box"> -->

<!--                 <div class="box-body"> -->
    <div class="col-xs-8" id="ttpoprint">
<style type="text/css">
.ttpoprint {
	font-family: "仿宋";
	font-size:20px;
}
.tablehead{ width:100%; height:30px; line-height:20px; text-align:center; float:left; font-size:30px; font-family:"黑体"}
</style>
<br>
          <!-- Box Comment -->
          <div class="box box-widget">
            <div class="box-header with-border">
              <div class="tablehead"><?= date('Y')?>年种植业保险申请书</div>
              <!-- /.user-block -->

              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
             <div class="box-header">岭南生态农业示范区：<?= ManagementArea::getAreaname($model->management_area)?><span class="navbar-right">单位：亩&nbsp;&nbsp;&nbsp;</span></div>
             <table width="100%" border="1" cellpadding="0" cellspacing="0" class="ttpoprint">
			  <tr height="30px">
			    <td width="10%" align="center">农场名称</td>
			    <td colspan="3" align="center"><?= $farm->farmname?></td>
			    <td align="center">法人<br>
			      姓名</td>
			    <td colspan="2" align="center"><?= $farm->farmername?></td>
			    <td width="8%" align="center">合同<br>
			      编号</td>
			    <td colspan="3" align="center"><?= $farm->contractnumber?></td>
			    </tr>
			  <tr height="30px">
			    <td align="center">被保险人<br>
			      姓名</td>
			    <td colspan="2" align="center"><?= $model->policyholder?></td>
			    <td align="center">身份证<br>
			      号码</td>
			    <td colspan="4" align="center"><?= $farm->cardid?></td>
			    <td width="8%" align="center">联系<br>
			      电话</td>
			    <td colspan="2" align="center"><?= $farm->telephone?></td>
			    </tr>
			  <tr height="30px">
			    <td rowspan="2" align="center">宜农林地<br>
			      面积</td>
			    <td width="8%" rowspan="2" align="center"><?= $farm->contractarea?></td>
			    <td width="8%" rowspan="2" align="center">种植<br>
			      结构</td>
			    <td width="10%" align="center">小麦</td>
			    <td width="9%" align="center">大豆</td>
			    <td width="8%" align="center">其它</td>
			    <td width="6%" rowspan="2" align="center">保险<br>
			      面积</td>
			    <td rowspan="2" align="center"><?= $model->insuredarea?></td>
			    <td align="center">小麦</td>
			    <td width="10%" align="center">大豆</td>
			    <td width="15%" align="center">其它</td>
			    </tr>
			  <tr height="30px">
			    <td align="center"><?= $model->wheat?></td>
			    <td align="center"><?= $model->soybean?></td>
			    <td align="center"><?= $model->other?></td>
			    <td align="center"><?= $model->insuredwheat?></td>
			    <td align="center"><?= $model->insuredsoybean?></td>
			    <td align="center"><?= $model->insuredother?></td>
			    </tr>
			   
			  <tr height="30px">
			    <td align="center"><p><br>
			      <br>
			      被<br>
			      保<br>
			      险<br>
			      人<br>
			      承<br>
			      诺</p>
			      <p><br>
			        <br>
		          </p></td>
			    <td colspan="10" align="left" valign="top">
			      <p><br>
			        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;我是岭南生态农业示范区<u><strong> <?= $farm->farmname?> 
			          </strong></u><?php if($model->nameissame) echo '农场承包人';else echo '租赁人';?><u><strong> <?php if($model->nameissame) echo $model->farmername;else echo $model->policyholder;?>  
		              </strong></u>，自愿参加<?= date('Y')?>年种植业保险，遵守农业保险相关法律、法规和政策性文件，自愿缴纳保费，履行保险协议相关义务，自愿选择保险公司为<u><strong> <?= Insurancecompany::find()->where(['id'=>$model->company_id])->one()['companynname']?> 
		              </strong></u>。本人保证参保作物品种、种植面积是本人种植和真实有效的，如出现虚假行为由本人承担一切责任。</p>
			        
			        </p>
			      <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;农场名称（盖章）：</p>
					<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;投保人姓名（签字）：</p>
					<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= datetozhongwen::dateto()?></p>
				</td>
			      </tr>
			     
			  <tr>
			  	<td height="30" align="center"><p>&nbsp;</p>
			  	  <p>管<br>
			  	    会<br>
			  	    职<br>
			  	    能<br>
			  	    科<br>
			  	    室<br>
			  	    意<br>
		  	      见</p>
			  	  <p>&nbsp;</p></td>
			  	<td colspan="10" align="left" valign="top"><p><br>

			  	  &nbsp;&nbsp;&nbsp;&nbsp;地产工作组意见：</p>
					<?php
					$fields = Insurancedck::attributesList();
					$dckInfo = Insurancedck::find()->where(['insurance_id'=>$model->id])->one();
					echo '<table width="100%">';
					foreach ($fields  as $key => $value) {
						echo '<tr>';
						echo '<td width="10%">';
						echo '&nbsp;&nbsp;&nbsp;&nbsp;';
						if ($dckInfo[$key]) echo '&radic;'; else echo '&Chi;';
						echo '</td>';
						echo '<td>';
						echo $value;
						echo '</td>';
						echo '</tr>';
						if($key == 'isoneself') {
							if($dckInfo[$key] == 0) {
								echo '<tr>';
								echo '<td whdti="10%">';
								echo '&nbsp;&nbsp;&nbsp;&nbsp;';
								if ($dckInfo['iswt']) echo '&radic;'; else echo '&Chi;';
								echo '</td>';
								echo '<td>';
								echo '提供委托书及委托人身份证';
								echo '</td>';
								echo '</tr>';
							}
						}


					}
					echo '</table>';
					?>
			  	  <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;负责人签字：</p>

					<p>
			  	    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			  	    <?= datetozhongwen::dateto()?>
			  	  </p>
			  	  <p>&nbsp;&nbsp;&nbsp;&nbsp;服务大厅意见：</p>
			  	  <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;负责人签字：</p>
			  	  <p>
			  	   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			  	    <?= datetozhongwen::dateto()?>
			  	  </p></td>
			  	</tr>
			  <tr>
			  	<td height="30" align="center"><p>&nbsp;</p>
			  	  <p>备</p>
			  	  <p>注</p>
			  	  <p>&nbsp;</p></td>
			  	<td colspan="10" align="center">&nbsp;</td>
			  	</tr>
			</table>
			<br>
				<?= html::a('返回',\Yii::$app->request->getReferrer(),['class'=>'btn btn-primary'])?>
            </div>
            <!-- /.box-body -->
           
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->


<!--                 </div> -->
<!--             </div> -->
        </div>
    </div>
</section>
</div>
