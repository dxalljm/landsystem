<?php 
use yii\helpers\Url;
use app\models\Farms;
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
              <input type="text" name="q" class="form-control" placeholder="搜索">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
          </form>
          <!-- /.search form -->
          <li class="header">导航栏</li>
          <?php 
          	$action = Yii::$app->controller->action->id;
	    	if($action == 'farmsmenu' or $action == 'farmercreate' or $action == 'leaseindex'){?>
	    	<li class="header"><?= Farms::find()->where(['id'=>$_GET['farms_id']])->one()['farmname']?></li>
				<li class="treeview">
                	<a href="<?= Url::to('index.php?r=farmer/farmercreate&farms_id='.$_GET['farms_id']) ?>"><i class="fa fa-dashboard"></i><span>承包</span></a>
           		</li>
           		<li class="treeview">
                	<a href="#"><i class="fa fa-dashboard"></i><span>转让</span></a>
           		</li>
           		<li class="treeview">
                	<a href="#"><i class="fa fa-dashboard"></i><span>租赁</span></a>
           		</li>
           		<li class="treeview">
                	<a href="#"><i class="fa fa-dashboard"></i><span>贷款</span></a>
           		</li>
           		<li class="treeview">
                	<a href="#"><i class="fa fa-dashboard"></i><span>纠纷</span></a>
           		</li>
           		<li class="treeview">
                	<a href="#"><i class="fa fa-dashboard"></i><span>贷款</span></a>
           		</li>
           		<li class="treeview">
                	<a href="#"><i class="fa fa-dashboard"></i><span>贷款</span></a>
           		</li>
           		<li class="treeview">
                	<a href="#"><i class="fa fa-dashboard"></i><span>贷款</span></a>
           		</li>
           		<li class="treeview">
                	<a href="#"><i class="fa fa-dashboard"></i><span>贷款</span></a>
           		</li>
           		<li class="treeview">
                	<a href="#"><i class="fa fa-dashboard"></i><span>贷款</span></a>
           		</li>
	    	<?php } else {?>
          
            
            <li class="treeview">
                <a href="<?= url::to('index.php?r=parcel/parcelindex') ?>"><i class="fa fa-dashboard"></i><span>宗地管理</span></a>
            </li>
            <li class="treeview">
                <a href="#"><i class="fa fa-dashboard"></i><span>菜单1</span></a>
            </li>
            <li class="treeview">
                <a href="#"><i class="fa fa-dashboard"></i><span>菜单1</span></a>
            </li>
            <li class="treeview">
                <a href="#"><i class="fa fa-dashboard"></i><span>菜单1</span></a>
            </li>
            <li class="treeview">
                <a href="#"><i class="fa fa-dashboard"></i><span>菜单1</span></a>
            </li>
            <?php }?>
        </ul>
    </section>
</aside>