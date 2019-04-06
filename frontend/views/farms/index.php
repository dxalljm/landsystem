<?php
/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<tr>
			<td width=15% align='right'>合作社</td>
			
				
			<td align='left' >
			
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
