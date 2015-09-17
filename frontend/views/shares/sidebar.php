<?php 
use yii\helpers\Url;
use app\models\Farms;
use app\models\MenuToUser;
use app\models\Mainmenu;
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
              <p>欢迎您 <?= yii::$app->user->identity->username?></p>
              <a href="<?= Url::to('index.php?r=site/logout') ?>"><i class="fa fa-circle text-success"></i> 退出</a>
            </div>
          </div>
          <!-- search form -->
          <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
              <input type="text" name="q" id="sidebarSearch"  list="search_list" class="form-control" placeholder="搜索">
            </div>
          </form>
          <!-- /.search form -->
          <li class="header">导航栏</li>
          <?php 
          	$controller = Yii::$app->controller->id;
          	$action = yii::$app->controller->action->id;
	    	if($action == 'farmsmenu' or $controller == 'plantpesticides' or $controller == 'farmer' or $controller == 'lease' or $controller == 'fireprevention' or $controller == 'dispute' or $controller == 'lease' or $controller == 'cooperativeoffarm' or $controller == 'employee' or $controller == 'plantingstructure' or $controller == 'plantinputproduct'){?>
	    	<?php if(isset($_GET['farms_id'])) {?>
	    	<li class="header text-light-blue"><?= Farms::find()->where(['id'=>$_GET['farms_id']])->one()['farmname']?></li>
	    	<?php }?>
				<li class="treeview">
                	<a href="<?= Url::to('index.php?r=farmer/farmercreate&farms_id='.$_GET['farms_id']) ?>"><i class="fa fa-user"></i><span>承包</span></a>
           		</li>
           		<li class="treeview">
                	<a href="#"><i class="fa fa-users"></i><span>转让</span></a>
           		</li>
           		<li class="treeview">
                	<a href="<?= Url::to('index.php?r=lease/leaseindex&farms_id='.$_GET['farms_id']) ?>"><i class="fa fa-street-view"></i><span>租赁</span></a>
           		</li>
           		<li class="treeview">
                	<a href="#"><i class="fa fa-university"></i><span>贷款</span></a>
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
                	<a href="<?= Url::to('index.php?r=plantinputproduct/plantinputproductindex&farms_id='.$_GET['farms_id']) ?>"><i class="fa fa-tint"></i><span>投入品</span></a>
           		</li>
           		<li class="treeview">
                	<a href="<?= Url::to('index.php?r=plantpesticides/plantpesticidesindex&farms_id='.$_GET['farms_id']) ?>"><i class="fa fa-exclamation-triangle"></i><span>农药</span></a>
           		</li>
           		<li class="treeview">
                	<a href="<?= Url::to('index.php?r=fireprevention/firepreventioncreate&farms_id='.$_GET['farms_id']) ?>"><i class="fa fa-fire-extinguisher"></i><span>防火工作</span></a>
           		</li>
           		
	    	<?php } else {?>
          
          	 <?php
                if(yii::$app->user->identity->username != 'admin') {
                    $menuliststr = MenuToUser::find()->where(['user_id'=>\Yii::$app->user->id])->one()['menulist'];
                    $menulistarr = explode(',', $menuliststr);

                    foreach($menulistarr as $val) {
                        $menu = Mainmenu::find()->where(['id'=>$val])->one();
                        echo "<li ><a href=" . Url::to('index.php?r='.$menu['menuurl']) . "><i class='fa fa-dashboard'></i><span>". $menu['menuname'] . "</span></a></li>";
                    }
                }
                ?>
	            
            <?php }?>
        </ul>
    </section>
</aside>
<datalist id="search_list">

</datalist>
<?php
$script = <<<JS

$('#sidebarSearch').autocomplete({
    serviceUrl: 'index.php?r=search/getsearch',
    params: {search: $(this).val()},
    lookup: function (query, done) {
        console.log(query);
    },
    onSelect: function (suggestion) {
        alert('You selected: ' + suggestion.value + ', ' + suggestion.data);
    }

});


JS;
$this->registerJs($script);
?>
