<?php 
use yii\helpers\Url;
use app\models\Farms;
use app\models\MenuToUser;
use app\models\Mainmenu;
use app\models\Reviewprocess;
use app\models\User;
?>
<aside class="main-sidebar">
    <section class="sidebar" style="height: auto;">
        <ul class="sidebar-menu">
        <!-- Sidebar user panel -->
          <div class="user-panel">
            <div class="pull-left image">
              <img src="images/xaioren.gif" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
              <p><?php
$h=date('G');
if ($h<11) echo '早上好';
else if ($h<13) echo '中午好';
else if ($h<17) echo '下午好';
else echo '晚上好';
?> <?= yii::$app->user->identity->realname?></p>
              <a href="<?= Url::to('index.php?r=site/logout') ?>"><i class="fa fa-circle text-success"></i> 退出</a>
              <a href="<?= Url::to('index.php?r=user/modfiyuserinfo') ?>">修改密码</a>
            </div>
          </div>
          <!-- search form -->
          <form action="" method="get" class="sidebar-form">
            <div class="input-group">
              <input type="text" name="q" id="sidebarSearch" class="form-control" placeholder="搜索">
            </div>
          </form>
          <!-- /.search form -->
          <li class="header">导航栏</li>
          <?php 
          	$controller = Yii::$app->controller->id;
          	$action = yii::$app->controller->action->id;
// 	    	if($action == 'collectionsend' or $action == 'farmsmenu' or $action == 'farmsttpomenu' or $action == 'farmssplit'or $action == 'farmstransfer' or $action == 'farmsttpozongdi' or $action == 'farmstozongdi'  or $controller == 'plantpesticides' or $controller == 'prevention' or $controller == 'breed' or $controller == 'loan' or  $controller == 'sales' or $controller == 'yields' or $controller == 'farmer' or $controller == 'lease' or $controller == 'fireprevention' or $controller == 'dispute' or $controller == 'lease' or $controller == 'cooperativeoffarm' or $controller == 'employee' or $controller == 'plantingstructure' or $controller == 'plantinputproduct'){
	    	if(isset($_GET['farms_id'])) {
          	?>
	    	<?php if(isset($_GET['farms_id'])) {?>
	    	<li class="header text-light-blue"><h4><?= Farms::find()->where(['id'=>$_GET['farms_id']])->one()['farmname']?></h4></li>
	    	<?php }?>
	    		<li class="treeview">
                	<a href="<?= Url::to('index.php?r=farms/farmsmenu&farms_id='.$_GET['farms_id']) ?>"><i class="fa fa-list"></i><span>业务菜单</span></a>
           		</li>
				<li class="treeview">
                	<a href="<?= Url::to('index.php?r=farmer/farmercreate&farms_id='.$_GET['farms_id']) ?>"><i class="fa fa-user"></i><span>法人信息</span></a>
           		</li>
           		<li class="treeview">
                	<a href="<?= Url::to('index.php?r=farms/farmsttpomenu&farms_id='.$_GET['farms_id']) ?>"><i class="fa fa-users"></i><span>转让</span></a>
           		</li>
           		<li class="treeview">
                	<a href="<?= Url::to('index.php?r=lease/leaseindex&farms_id='.$_GET['farms_id']) ?>"><i class="fa fa-street-view"></i><span>租赁</span></a>
           		</li>
           		<li class="treeview">
                	<a href="<?= Url::to('index.php?r=loan/loanindex&farms_id='.$_GET['farms_id']) ?>"><i class="fa fa-university"></i><span>贷款</span></a>
           		</li>
           		<li class="treeview">
                	<a href="<?= Url::to('index.php?r=dispute/disputeindex&farms_id='.$_GET['farms_id']) ?>"><i class="fa fa-commenting"></i><span>纠纷</span></a>
           		</li>
           		<li class="treeview">
                	<a href="<?= Url::to('index.php?r=cooperativeoffarm/cooperativeoffarmindex&farms_id='.$_GET['farms_id']) ?>"><i class="fa fa-briefcase"></i><span>合作社信息</span></a>
           		</li>
           		<li class="treeview">
                	<a href="<?= Url::to('index.php?r=employee/employeefathers&farms_id='.$_GET['farms_id']) ?>"><i class="fa fa-user-plus"></i><span>雇工信息</span></a>
           		</li>
           		<li class="treeview">
                	<a href="<?= Url::to('index.php?r=plantingstructure/plantingstructureindex&farms_id='.$_GET['farms_id']) ?>"><i class="fa fa-sun-o"></i><span>种植结构</span></a>
           		</li>
           		<li class="treeview">
                	<a href="<?= Url::to('index.php?r=fireprevention/firepreventioncreate&farms_id='.$_GET['farms_id']) ?>"><i class="fa fa-fire-extinguisher"></i><span>防火工作</span></a>
           		</li>
           		<li class="treeview">
                	<a href="<?= Url::to('index.php?r=yields/yieldsindex&farms_id='.$_GET['farms_id']) ?>"><i class="fa fa-balance-scale"></i><span>产量信息</span></a>
           		</li>
           		<li class="treeview">
                	<a href="<?= Url::to('index.php?r=sales/salesindex&farms_id='.$_GET['farms_id']) ?>"><i class="fa fa-cart-arrow-down"></i><span>销售信息</span></a>
           		</li>
           		<li class="treeview">
                	<a href="<?= Url::to('index.php?r=breed/breedcreate&farms_id='.$_GET['farms_id']) ?>"><i class="fa fa-cart-arrow-down"></i><span>养殖信息</span></a>
           		</li>
           		<li class="treeview">
                	<a href="<?= Url::to('index.php?r=prevention/preventionindex&farms_id='.$_GET['farms_id']) ?>"><i class="fa fa-cart-arrow-down"></i><span>防疫信息</span></a>
           		</li>
           		
	    	<?php } else {?>
          
          	 <?php
                if(yii::$app->user->identity->username != 'admin') {
                    $menuliststr = MenuToUser::find()->where(['role_id'=>User::getItemname()])->one()['menulist'];
                    $menulistarr = explode(',', $menuliststr);
	                   foreach($menulistarr as $val) {
	                   $menu = Mainmenu::find()->where(['id'=>$val])->one();
	                   if($menu['menuurl'] == 'dropdown') {
	                   		if(yii::$app->controller->id == 'nation' or yii::$app->controller->id == 'plant' or yii::$app->controller->id == 'inputproduct' or yii::$app->controller->id == 'pesticides' or yii::$app->controller->id == 'goodseed' or yii::$app->controller->id == 'cooperative' or yii::$app->controller->id == 'disputetype')
	                   			echo '<li class="active treeview">';
	                   		else 
	                   			echo '<li class="treeview">';
              				echo '<a href="#">';
                			echo '<i class="fa fa-dashboard"></i> <span>数据管理</span> <i class="fa fa-angle-left pull-right"></i>';
              				echo '</a>';
              				echo '<ul class="treeview-menu">';
                			echo '<li><a href="'.Url::to('index.php?r=nation/nationindex').'"><i class="fa fa-circle-o"></i>民族管理</a></li>';
	                       	echo '<li><a href="'.Url::to('index.php?r=plant/plantindex').'"><i class="fa fa-circle-o"></i>作物管理</a></li>';
	                       	echo '<li><a href="'.Url::to('index.php?r=inputproduct/inputproductindex').'"><i class="fa fa-circle-o"></i>投入品管理</a></li>';
	                       	echo '<li><a href="'.Url::to('index.php?r=inputproductbrandmodel/inputproductbrandmodelindex').'"><i class="fa fa-circle-o"></i>投入品品牌类型</a></li>';
	                       	echo '<li><a href="'.Url::to('index.php?r=pesticides/pesticidesindex').'"><i class="fa fa-circle-o"></i>农药管理</a></li>';
	                       	echo '<li><a href="'.Url::to('index.php?r=goodseed/goodseedindex').'"><i class="fa fa-circle-o"></i>良种管理</a></li>';
	                       	echo '<li><a href="'.Url::to('index.php?r=cooperative/cooperativeindex').'"><i class="fa fa-circle-o"></i>合作社管理</a></li>';
	                       	echo '<li><a href="'.Url::to('index.php?r=disputetype/disputetypeindex').'"><i class="fa fa-circle-o"></i>纠纷类型</a></li>';
	                       	echo '<li><a href="'.Url::to('index.php?r=breedtype/breedtypeindex').'"><i class="fa fa-circle-o"></i>养殖种类</a></li>';
	                       	echo '<li><a href="'.Url::to('index.php?r=infrastructuretype/infrastructuretypeindex').'"><i class="fa fa-circle-o"></i>基础设施类型</a></li>';
	                       	echo '<li><a href="'.Url::to('index.php?r=projecttype/projecttypeindex').'"><i class="fa fa-circle-o"></i>项目类型</a></li>';
	                       	echo '<li><a href="'.Url::to('index.php?r=disasterypte/disastertypeindex').'"><i class="fa fa-circle-o"></i>灾害类型</a></li>';
	                       	echo '<li><a href="'.Url::to('index.php?r=machinetype/machinetypeindex').'">机具类型</a></li>';
              				echo '</ul>';
            				echo '</li>';
	                     	
	                   } else  {
	                   	 	echo '<li>';
	                   		echo '<a href="' . Url::to('index.php?r='.$menu['menuurl']) . '">';
	                   		echo '<i class="fa fa-calendar"></i> <span>'. $menu['menuname'] .'</span>';
	                   		if($menu['menuurl'] == 'reviewprocess/reviewprocessindex')
	                   			echo Reviewprocess::getUserProcessCount();
	                   		echo '</a>';
	                   		echo '</li>';
// 	                       	echo "<li ><a href=""><i class='fa fa-dashboard'></i><span>" "</span></a></li>";
	                   }
					}
                }
                ?>
	            
            <?php }?>
        </ul>
    </section>
</aside>
<script type="text/javascript" charset="utf-8">
  var json = <?= Farms::searchAll() ?>;
  $('#sidebarSearch').autocomplete({
      lookup: json,
      formatResult: function (json) {
        return json.data;
      },
      onSelect: function (suggestion) {
        location.href = suggestion.url;
        $(this).val(suggestion.data);
        
      }
  });
</script>
