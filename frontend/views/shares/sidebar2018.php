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
<div class="sidebar" data-active-color="sgreen" data-background-color="green" data-image="images/sidebar-1.jpg">
	<app-sidebar-cmp>

		<div class="logo">
			<div class="logo-normal">
				<a class="simple-text" href="https://front.lngwh.gov">
					<strong>岭南管委会</strong>
				</a>
			</div>

<!--			<div class="logo-img">-->
<!--				<img src="/vendor/bower/AdminLET/dist/img/angular2-logo-white.png">-->
<!--			</div>-->
		</div>


		<div class="sidebar-wrapper">

			<div class="user">
				<div class="photo">
					<img src="images/xaioren.gif">
				</div>
				<div class="info">
					<a class="collapsed" data-toggle="collapse" href="#collapseExample" aria-expanded="false">
                        <span>
                            <strong><?= Yii::$app->user->identity->realname?></strong>
                            <b class="caret"></b>
                        </span>
					</a>
					<div class="collapse" id="collapseExample" aria-expanded="false" style="height: 0px;">
						<ul class="nav">
							<li>
								<a href="<?= Url::to('index.php?r=user/modfiyuserinfo') ?>">
									<span class="sidebar-mini"><i class="fa fa-edit"></i></span>
									<span class="sidebar-normal">修改密码</span>
								</a>
							</li>
							<li>
								<a href="#" data-toggle="control-sidebar">
									<span class="sidebar-mini"><i class="fa fa-cog"></i></span>
									<span class="sidebar-normal">设置</span>
								</a>
							</li>
							<li>
								<a href="<?= Url::to('index.php?r=site/logout') ?>">
									<span class="sidebar-mini"><i class="fa fa-sign-out"></i></span>
									<span class="sidebar-normal">退出</span>
								</a>
							</li>
						</ul>

					</div>
				</div>
				<div style="position:relative;left:25px;">
					<ul class="nav">
						<form action="" class="sidebar-form">
							<div class="">
								<input type="text" id="sidebarSearch" class="form-control" placeholder="搜索" style="width:80%;">
							</div>
						</form>
					</ul>
				</div>
			</div>
			<!---->
			<div class="nav-container">
				<ul class="nav">
					<!----><li routerlinkactive="active" class="active">

						<!----><a href="#">
							<i class="fa fa-reorder"></i>
							导航栏<?= \app\models\Help::phones();?>
						</a>
						<!---->
						<!---->
					</li>
					<?php
					$controller = Yii::$app->controller->id;
					$action = yii::$app->controller->action->id;
					if(isset($_GET['farms_id'])) {
						$farm = Farms::find()->where(['id'=>$_GET['farms_id']])->one();
						$menulistarr = Farms::getBusinessmenu();
						$farmerCardID = $farm['cardid'];
						Yii::$app->params['farmerCardID'] = $farm['cardid'];
						echo '<li routerlinkactive="active">';
						echo '<a href="'.Url::to(['farms/farmsmenu','farms_id'=>$_GET['farms_id']]) .'"><i class="fa fa-list"></i><p><span class="text-white">'.$farm['farmname'].'</span> 的业务菜单</p></a>';
						echo '</li>';
						foreach ($menulistarr as $menu) {

							$class = explode('/',$menu['menuurl']);
							if(yii::$app->controller->id == $class[0] and yii::$app->controller->action->id !== 'farmsmenu')
								echo '<li class="active" routerlinkactive="active">';
							else
								echo '<li routerlinkactive="active">';
							if($menu['menuname'] == '过户转让' or $menu['menuname'] == '贷款') {
								$lockinfo = Lockstate::isLoanLocked($_GET['farms_id']);
								if ($lockinfo['state'] and !Lockstate::isUnlockloan($_GET['farms_id'])) {
									echo '<a href="#"><i class="' . $menu['class'] . '"></i><p>' . $menu['menuname'] . '</p></a>';
								} else {
									echo '<a href="' . Url::to([$menu['menuurl'], 'farms_id' => $_GET['farms_id']]) . '"><i class="' . $menu['class'] . '"></i><p>' . $menu['menuname'] . '</p></a>';
								}
							} else {
								echo '<a href="' . Url::to([$menu['menuurl'], 'farms_id' => $_GET['farms_id']]) . '"><i class="' . $menu['class'] . '"></i><p>' . $menu['menuname'] . '</p></a>';
							}
							echo '</li>';
						} } else {
							$menulistarr = explode(',',Yii::$app->getUser()->getIdentity()->mainmenu);
							$menus = Mainmenu::find()->where(['id'=>$menulistarr])->orderBy('sort asc')->all();
							foreach($menus as $menu) {
								$class = explode('/',$menu['menuurl']);

								if($menu['menuurl'] == 'taskdropdown') {
									if(Yii::$app->controller->id == 'reviewprocess') {
										echo '<li class="active" routerlinkactive="active">';
										echo '<a class="" data-toggle="collapse" aria-expanded="true" href="#reviewprocess">';
										echo '<i class="fa fa-dashboard"></i>';
										echo '<p>任务<b class="caret"></b></p>';
										echo '</a>';
										echo '<div id="reviewprocess" class="collapse in" aria-expanded="true" style="">';
									}
									else {
										echo '<li routerlinkactive="active">';
										echo '<a class="collapsed" data-toggle="collapse" aria-expanded="false" href="#reviewprocess">';
										echo '<i class="fa fa-dashboard"></i>';
										echo '<p>任务<b class="caret"></b></p>';
										echo '</a>';
										echo '<div id="reviewprocess" class="collapse" aria-expanded="false" style="height: 0px;">';
									}


									echo '<ul class="nav">';
									if(Yii::$app->controller->action->id == 'reviewprocessindex') {
										echo '<li class="active"><a href="'.Url::to(['reviewprocess/reviewprocessindex']).'">审核任务<small class="label pull-right bg-red count2"></small></a></li>';
									} else {
										echo '<li><a href="'.Url::to(['reviewprocess/reviewprocessindex']).'">审核任务<small class="label pull-right bg-red count2"></small></a></li>';
									}
									if(Yii::$app->controller->action->id == 'reviewprocesswait') {
										echo '<li class="active"><a href="'.Url::to(['reviewprocess/reviewprocesswait']).'">待办任务<small class="label pull-right bg-red count0"></small></a></li>';
									} else {
										echo '<li><a href="'.Url::to(['reviewprocess/reviewprocesswait']).'">待办任务<small class="label pull-right bg-red count0"></small></a></li>';
									}
									if(Yii::$app->controller->action->id == 'reviewprocessing') {
										echo '<li class="active"><a href="'. Url::to(['reviewprocess/reviewprocessing']).'">正在办理<small class="label pull-right bg-red count4"></small></a></li>';
									} else {
										echo '<li><a href="'. Url::to(['reviewprocess/reviewprocessing']).'">正在办理<small class="label pull-right bg-red count4"></small></a></li>';
									}
									if(Yii::$app->controller->action->id == 'reviewprocessfinished') {
										echo '<li class="active"><a href="'.Url::to(['reviewprocess/reviewprocessfinished']).'">已完成任务<small class="label pull-right bg-green count6"></small></a></li>';
									} else {
										echo '<li><a href="'.Url::to(['reviewprocess/reviewprocessfinished']).'">已完成任务<small class="label pull-right bg-green count6"></small></a></li>';
									}
									if(Yii::$app->controller->action->id == 'reviewprocessreturn') {
										echo '<li class="active"><a href="'. Url::to(['reviewprocess/reviewprocessreturn']).'">退回任务<small class="label pull-right bg-red count8"></small></a></li>';
									} else {
										echo '<li><a href="'. Url::to(['reviewprocess/reviewprocessreturn']).'">退回任务<small class="label pull-right bg-red count8"></small></a></li>';
									}

									if(Yii::$app->controller->action->id == 'reviewprocesscacle') {
										echo '<li class="active"><a href="'. Url::to(['reviewprocess/reviewprocesscacle']).'">撤消任务<small class="label pull-right bg-green count9"></small></a></li>';
									} else {
										echo '<li><a href="'. Url::to(['reviewprocess/reviewprocesscacle']).'">撤消任务<small class="label pull-right bg-green count9"></small></a></li>';
									}

									echo '</ul>';
									echo '</div>';
								} elseif($menu['menuurl'] == 'dropdown') {
									if(yii::$app->controller->id == 'nation' or yii::$app->controller->id == 'plant' or yii::$app->controller->id == 'inputproduct' or yii::$app->controller->id == 'pesticides' or yii::$app->controller->id == 'goodseed' or yii::$app->controller->id == 'cooperative' or yii::$app->controller->id == 'disputetype')
										echo '<li class="active" routerlinkactive="active">';
									else
										echo '<li routerlinkactive="active">';
									echo '<a data-toggle="collapse" href="#">';
									echo '<i class="fa fa-dashboard"></i>';
									echo '<p>数据管理<b class="caret"></b></p>';
									echo '</a>';
									echo '<ul class="nav">';
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
									if(Yii::$app->controller->action->id == $class[1]) {
										echo '<li class="active" routerlinkactive="active">';
									} else {
										echo '<li routerlinkactive="active">';
									}

									echo '<a href="' . Url::to('index.php?r='.$menu['menuurl']) . '">';
									echo '<i class="fa fa-calendar"></i>';
//									echo '<p>';
									echo $menu['menuname'];
									if($menu['menuurl'] == 'collection/collectionindex') {
										echo Collection::getCollectionCount();
									}
									if($menu['menuurl'] == 'huinong/huinonglist') {
										echo huinong::getHuinongCount();
									}
//									echo '</p>';
									echo '</a>';
									echo '</li>';
								}
							}
					}?>
				</ul>
			</div>

		</div>
	</app-sidebar-cmp>
	<div class="sidebar-background" style="background-image: url('images/sidebar-1.jpg')"></div>
</div>

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
