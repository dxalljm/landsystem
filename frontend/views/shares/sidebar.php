<?php 
use yii\helpers\Url;
use app\models\Farms;
use app\models\MenuToUser;
use app\models\Mainmenu;
use app\models\Reviewprocess;
use app\models\User;
use app\models\Huinong;
use app\models\Lockstate;
use app\models\Collection;
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
// $h=date('G');
// if ($h<11) echo '早上好';
// else if ($h<13) echo '中午好';
// else if ($h<17) echo '下午好';
// else echo '晚上好';
				  
?> <?= '&nbsp;'.yii::$app->getUser()->identity->realname?></p>
              <a href="<?= Url::to('index.php?r=site/logout') ?>"><i class="fa fa-circle text-success"></i> 退出</a>
              <a href="<?= Url::to('index.php?r=user/modfiyuserinfo') ?>">修改密码</a>
            </div>
          </div>
          <!-- search form -->
          <form action="" class="sidebar-form">
            <div class="input-group">
              <input type="text" id="sidebarSearch" class="form-control" placeholder="搜索">
            </div>
          </form>
          <!-- /.search form -->
          <li class="header">导航栏<?= \app\models\Help::phones();?></li>
          <?php 
          	$controller = Yii::$app->controller->id;
          	$action = yii::$app->controller->action->id;
	    	if(isset($_GET['farms_id'])) {
				$menulistarr = Farms::getBusinessmenu();
				$farmerCardID = Farms::find()->where(['id'=>$_GET['farms_id']])->one()['cardid'];
          	?>
	    	
	    		<li class="treeview">
                	<a href="<?= Url::to(['farms/farmsmenu','farms_id'=>$_GET['farms_id']]) ?>"><i class="fa fa-list"></i><span><span class="text-blue"><?php $farm = Farms::find()->where(['id'=>$_GET['farms_id']])->one(); Yii::$app->params['farmerCardID'] = $farm['cardid'];echo $farm['farmname'];?></span>的业务菜单</span></a>
           		</li>
           	<?php foreach ($menulistarr as $menu) {
           		
				$class = explode('/',$menu['menuurl']);
				if(yii::$app->controller->id == $class[0] and yii::$app->controller->action->id !== 'farmsmenu')
           			echo '<li class="treeview active">';
				else
					echo '<li class="treeview">';
				if($menu['menuname'] == '过户转让' or $menu['menuname'] == '贷款') {
					$lockinfo = Lockstate::isLoanLocked($_GET['farms_id']);
					if ($lockinfo['state'] and !Lockstate::isUnlockloan($_GET['farms_id'])) {
						echo '<a href="#"><i class="' . $menu['class'] . '"></i><span>' . $menu['menuname'] . '</span></a>';
					} else {
						echo '<a href="' . Url::to([$menu['menuurl'], 'farms_id' => $_GET['farms_id']]) . '"><i class="' . $menu['class'] . '"></i><span>' . $menu['menuname'] . '</span></a>';
					}
				} else {
					echo '<a href="' . Url::to([$menu['menuurl'], 'farms_id' => $_GET['farms_id']]) . '"><i class="' . $menu['class'] . '"></i><span>' . $menu['menuname'] . '</span></a>';
				}
           		echo '</li>';
           	?>
           		
	    	<?php } } else {?>
          	 <?php
                if(yii::$app->user->identity->username !== 'admin') {
					$menulistarr = explode(',',Yii::$app->getUser()->getIdentity()->mainmenu);
					$menus = Mainmenu::find()->where(['id'=>$menulistarr])->orderBy('sort asc')->all();
	                   foreach($menus as $menu) {
	                   
						   $class = explode('/',$menu['menuurl']);
						   if(yii::$app->controller->id == $class[0]) {
							   echo '<li class="treeview active">';
						   }
						   else
							   echo '<li class="treeview">';
			 if($menu['menuurl'] == 'taskdropdown') {

			 ?>

<!--			<li class="dropdown">-->
<!--				<a href="#" class="dropdown-toggle" data-toggle="dropdown">任务--><?php //echo Reviewprocess::getUserProcessAllCount();?><!-- <span class="caret"></span></a>-->
			<a href="#"><i class="fa fa-dashboard"></i> <span>任务<small class="label pull-right bg-red allcount"></small></span></a>
				<ul class="treeview-menu">
					<li><a href="<?= Url::to(['reviewprocess/reviewprocessindex'])?>">审核任务<small class="label pull-right bg-red count2"></small></a></li>
					<li><a href="<?= Url::to(['reviewprocess/reviewprocesswait'])?>">待办任务<small class="label pull-right bg-red count0"></small></a></li>
					<li><a href="<?= Url::to(['reviewprocess/reviewprocessing'])?>">正在办理<small class="label pull-right bg-red count4"></small></a></li>
					<li><a href="<?= Url::to(['reviewprocess/reviewprocessfinished'])?>">已完成任务<small class="label pull-right bg-green count6"></small></a></li>
					<li><a href="<?= Url::to(['reviewprocess/reviewprocessreturn'])?>">退回任务<small class="label pull-right bg-red count8"></small></a></li>
					<li><a href="<?= Url::to(['reviewprocess/reviewprocesscacle'])?>">撤消任务<small class="label pull-right bg-green count9"></small></a></li>
				</ul>
				<?php }
	                   elseif($menu['menuurl'] == 'dropdown') {
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
//	                   		if($menu['menuurl'] == 'reviewprocess/reviewprocessindex')
//	                   			echo Reviewprocess::getUserProcessCount();
	                   		if($menu['menuurl'] == 'huinong/huinonglist') {
	                   			echo huinong::getHuinongCount();
	                   		}
				 			if($menu['menuurl'] == 'collection/collectionindex') {
					 			echo Collection::getCollectionCount();
				 			}
	                   		echo '</a>';
	                   		echo '</li>';
// 	                       	echo "<li ><a href=""><i class='fa fa-dashboard'></i><span>" "</span></a></li>";
	                   }
					}
                }
                ?>
	            
            <?php }?>
<!--        </ul>--><?php //}?>
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
