<?php
use app\models\MenuToUser;
use app\models\Mainmenu;
use yii\helpers\Url;
use app\models\Reviewprocess;
use app\models\User;
use app\models\Collection;
use app\models\Tempauditing;
use app\models\Huinong;
use app\models\Insurance;
use app\models\Farms;
use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use app\models\Indexecharts;
if(isset($_GET['farms_id'])) {
    $cardid = Farms::find()->where(['id'=>$_GET['farms_id']])->one()['cardid'];
    defined('FARMER_CARDID') or define('FARMER_CARDID',$cardid);
}

?>
<script type="text/javascript" src="vendor/bower/CircleLoader/jquery.shCircleLoader-min.js"></script>
<link href="/vendor/bower/CircleLoader/jquery.shCircleLoader.css" rel="stylesheet">
<style>
    li{
        width:auto;margin:0px 0px;;
    }
</style>
<header class="main-header">

    <a href="/" class="logo">
        <span class="logo-lg"><b>岭南管委会</b></span>
    </a>

    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>
        <div class="">
            <ul class="nav navbar-nav">

                <?php
                if(yii::$app->user->identity->username != 'admin') {
                    //$menuliststr = MenuToUser::find()->where(['role_id'=>User::getItemname()])->one()['menulist'];
                    $menuArray = explode(',',Yii::$app->getUser()->getIdentity()->mainmenu);
                    $menus = Mainmenu::find()->where(['id'=>$menuArray])->orderBy('sort asc')->all();
//                var_dump($menulistarr);
//					asort($menulistarr);
                    foreach($menus as $menu) {
                        
                        if($menu['menuname'] == '任务') {
                        ?>
                        <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">任务<small class="label pull-right bg-red allcount"></small><span class="caret"></span></a>

                        <ul class="dropdown-menu" role="menu">
                        <li><a href="<?= Url::to(['reviewprocess/reviewprocessindex'])?>">审核任务<small class="label pull-right bg-red count2"></small></a></li>


                            <li><a href="<?= Url::to(['reviewprocess/reviewprocesswait'])?>">待办任务<small class="label pull-right bg-red count0"></small></a></li>
                            <li><a href="<?= Url::to(['reviewprocess/reviewprocessing'])?>">正在办理<small class="label pull-right bg-green count4"></small></a></li>

                        <li><a href="<?= Url::to(['reviewprocess/reviewprocessfinished'])?>">已完成任务<small class="label pull-right bg-green count6"></small></a></li>
                        <li><a href="<?= Url::to(['reviewprocess/reviewprocessreturn'])?>">退回任务<small class="label pull-right bg-red count8"></small></a></li>
                        <li><a href="<?= Url::to(['reviewprocess/reviewprocesscacle'])?>">撤消任务<small class="label pull-right bg-green count9"></small></a></li>
                    </ul>
                <?php }
                if($menu['menuurl'] == 'dropdown') {
                ?>
                        	<li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">数据管理 <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="<?= Url::to('index.php?r=nation/nationindex')?>">民族管理</a></li>
                        <li class="divider"></li>
                        <li><a href="<?= Url::to('index.php?r=plant/plantindex')?>">作物管理</a></li>
                        <li class="divider"></li>
                        <li><a href="<?= Url::to('index.php?r=inputproduct/inputproductindex')?>">投入品管理</a></li>
                        
                        <li><a href="<?= Url::to('index.php?r=inputproductbrandmodel/inputproductbrandmodelindex')?>">投入品品牌型号</a></li>
                        
                        <li><a href="<?= Url::to('index.php?r=pesticides/pesticidesindex')?>">农药管理</a></li>
                        <li class="divider"></li>
                        <li><a href="<?= Url::to('index.php?r=goodseed/goodseedindex')?>">良种管理</a></li>
                        <li class="divider"></li>
                        <li><a href="<?= Url::to('index.php?r=cooperative/cooperativeindex')?>">合作社管理</a></li>
                        <li class="divider"></li>
                        <li><a href="<?= Url::to('index.php?r=disputetype/disputetypeindex')?>">纠纷类型</a></li>
                        <li class="divider"></li>
                        <li><a href="<?= Url::to('index.php?r=breedtype/breedtypeindex')?>">养殖种类</a></li>
                        <li class="divider"></li>
                        <li><a href="<?= Url::to('index.php?r=infrastructuretype/infrastructuretypeindex')?>">基础设施类型</a></li>
                        <li class="divider"></li>
                        <li><a href="<?= Url::to('index.php?r=projecttype/projecttypeindex')?>">项目类型</a></li>
                        <li class="divider"></li>
                        <li><a href="<?= Url::to('index.php?r=disastertype/disastertypeindex')?>">灾害类型</a></li>
                        <li class="divider"></li>
                        <li><a href="<?= Url::to('index.php?r=machinetype/machinetypeindex')?>">机具类型</a></li>
                        <li class="divider"></li>
                        <li><a href="<?= Url::to('index.php?r=insurancecompany/insurancecompanyindex')?>">保险公司管理</a></li>
                    </ul>

                        <?php } else {
							if($menu['menuname'] !== '任务')
                                echo '<li width="auto"><a href="' . Url::to('index.php?r=' . $menu['menuurl']) . '">' . $menu['menuname'];

                        if($menu['menuurl'] == 'huinong/huinonglist') {
                        	echo huinong::getHuinongCount();
                        }
                        if($menu['menuurl'] == 'insurance/insurancefwdt') {
                            echo Insurance::getUserfwdtCount();
                        }
                        if($menu['menuurl'] == 'insurance/insurancecheckup') {
                            echo Insurance::getUserdckCount();
                        }
                        if($menu['menuurl'] == 'machineapply/machineapplyindex') {
                            echo \app\models\Machineapply::getCount();
                        }
                        if($menu['menuurl'] == 'collection/collectionindex') {
                            echo Collection::getCollectionCount();
                        }
                        if($menu['menuurl'] == 'print/printindex') {
                        	echo Insurance::getNoPrint();                       }
                        echo "</a></li>";

                    } 
                    
                    ?>

               
                <?php }} 
//                 echo "<li >";
//                 echo Html::a('图库','#', [
//             			'onclick' => "javascript:window.open('".Url::to(['photogallery/photogalleryindex'])."','','width=1200,height=800,top=50,left=80, location=no, toolbar=no, status=no, menubar=no, resizable=no, scrollbars=yes');return false;",
            
//             	]);
//                 echo "</a></li>";
                $tempauditing = Tempauditing::find()->where(['tempauditing'=>Yii::$app->getUser()->id,'state'=>1])->andWhere('begindate<='.strtotime(date('Y-m-d')).' and enddate>='.strtotime(date('Y-m-d')))->one();
//                var_dump($tempauditing);exit;
                if($tempauditing) {?>
                <li class="dropdown">
                       <a href="#" class="dropdown-toggle" data-toggle="dropdown">任务<small class="label pull-right bg-red allcount"></small><span class="caret"></span></a>

                       <ul class="dropdown-menu" role="menu">
                        <li><a href="<?= Url::to(['reviewprocess/reviewprocessindex'])?>">审核任务<small class="label pull-right bg-red count2"></small></a></li>


                            <li><a href="<?= Url::to(['reviewprocess/reviewprocesswait'])?>">待办任务<small class="label pull-right bg-red count0"></small></a></li>
                            <li><a href="<?= Url::to(['reviewprocess/reviewprocessing'])?>">正在办理<small class="label pull-right bg-red count4"></small></a></li>

                        <li><a href="<?= Url::to(['reviewprocess/reviewprocessfinished'])?>">已完成任务<small class="label pull-right bg-green count6"></small></a></li>
                        <li><a href="<?= Url::to(['reviewprocess/reviewprocessreturn'])?>">退回任务<small class="label pull-right bg-red count8"></small></a></li>
                        <li><a href="<?= Url::to(['reviewprocess/reviewprocesscacle'])?>">撤消任务<small class="label pull-right bg-green count9"></small></a></li>
                    </ul>
                <?php }
                ?>
            </ul>

            <script>
                $(function () {
                    $("[data-toggle='popover']").popover();
                });
            </script>
           <div class="navbar-custom-menu">
               <ul class="nav navbar-nav">
                   <li class="text-white">
                   <a><?php echo User::getYear().'年度';?>    </a>
<!--                        <a href="javascript:void(0);" id="currentTime">--><?//= date('Y年m月j日')?><!--</a>-->
                   </li>
                   <li class="text-white">
                       <a>版本：V2.9.20.00</a>
                       <!--                        <a href="javascript:void(0);" id="currentTime">--><?//= date('Y年m月j日')?><!--</a>-->
                   </li>
                   <li>
                       <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                   </li>

<div id="dialogWait" title="正在生成数据...">
	<?= Html::img('images/wait.gif')?>
</div>
               </ul>
           </div>
        </div>
    </nav>
</header>
<aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">

        <li class=""><a href="#control-sidebar-home-tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-home"></i></a></li>

    </ul>
    <?php
    $echartsPlate = User::getEcharts();

    $indexecharts = Indexecharts::find()->where(['user_id'=>Yii::$app->getUser()->id])->one();
    if($indexecharts)
        $indexechartsModel = Indexecharts::findOne($indexecharts['id']);
    else
        $indexechartsModel = new Indexecharts();
    ?>
    <div class="tab-content">
        <!-- Tab panes -->
        <div id="control-sidebar-theme-demo-options-tab" class="tab-pane active">
            <div>
                <h4 class="control-sidebar-heading">首页图表设置</h4>
                <?php $form = ActiveFormrdiv::begin(['action'=>Url::to(['user/userindexecharts','user_id'=>Yii::$app->getUser()->id])]); ?>
                <div class="form-group">
                    <label class="control-sidebar-subheading">
                        <table>
                            <tr>
                                <td width="50%">第一行中间</td>
                                <td><?= $form->field($indexechartsModel,'onem')->dropDownList($echartsPlate,['prompt'=>'不显示'])->error(false)->label(false)?></td>
                            </tr>
                        </table>
                    </label>
                </div>
                <div class="form-group">
                    <label class="control-sidebar-subheading">
                        <table>
                            <tr>
                                <td width="50%">第一行右侧</td>
                                <td><?= $form->field($indexechartsModel,'oner')->dropDownList($echartsPlate,['prompt'=>'不显示'])->error(false)->label(false)?></td>
                            </tr>
                        </table>
                    </label>
                </div>
                <div class="form-group">
                    <label class="control-sidebar-subheading">
                        <table>
                            <tr>
                                <td width="50%">第二行左侧</td>
                                <td><?= $form->field($indexechartsModel,'twol')->dropDownList($echartsPlate,['prompt'=>'不显示'])->error(false)->label(false)?></td>
                            </tr>
                        </table>
                    </label>
                </div>
                <div class="form-group">
                    <label class="control-sidebar-subheading">
                        <table>
                            <tr>
                                <td width="50%">第二行中间</td>
                                <td><?= $form->field($indexechartsModel,'twom')->dropDownList($echartsPlate,['prompt'=>'不显示'])->error(false)->label(false)?></td>
                            </tr>
                        </table>
                    </label>
                </div>
                <div class="form-group">
                    <label class="control-sidebar-subheading">
                        <table>
                            <tr>
                                <td width="50%">第二行右侧</td>
                                <td><?= $form->field($indexechartsModel,'twor')->dropDownList($echartsPlate,['prompt'=>'不显示'])->error(false)->label(false)?></td>
                            </tr>
                        </table>
                    </label>
                </div>
                <div class="form-group">
                    <?= Html::submitButton('更新', ['class' => 'btn btn-default']) ?>
                </div>
                <?php ActiveFormrdiv::end(); ?>
            </div>
        </div>
        <!-- /.tab-pane -->
    </div>
</aside>
<script>
$( "#dialogWait" ).dialog({
	autoOpen: false,
	width: 300,
	open: function (event, ui) {
		$(".ui-dialog-titlebar-close", $(this).parent()).hide();
	}
});
//window.onbeforeunload=function (){
////	alert("===onbeforeunload===");
//    var action = "<?//= Yii::$app->controller->action->id?>//";
//	if(event.clientX>document.body.clientWidth && event.clientY < 0 || event.altKey){
////		alert("你关闭了浏览器");
//	}else{
//        if(action !== 'basedataverifylist')
//        {
//            $("#dialogWait").dialog("open");
//        }
//	}
//}
$(document).ready(function () {
    $.getJSON('index.php?r=search/getprocesscount', {id: 'all'}, function (data) {
        $('.allcount').html(data.data);
    });
    $.getJSON('index.php?r=search/getprocesscount', {id: 0}, function (data) {
        $('.count0').html(data.data);
    });
    $.getJSON('index.php?r=search/getprocesscount', {id: 2}, function (data) {
        $('.count2').html(data.data);
    });
    $.getJSON('index.php?r=search/getprocesscount', {id: 4}, function (data) {
        $('.count4').html(data.data);
    });
    $.getJSON('index.php?r=search/getprocesscount', {id:6}, function (data) {
//         alert(data.data);
        $('.count6').html(data.data);
    });
    $.getJSON('index.php?r=search/getprocesscount', {id: 8}, function (data) {
        $('.count8').html(data.data);
    });
    $.getJSON('index.php?r=search/getprocesscount', {id: 9}, function (data) {
        $('.count9').html(data.data);
    });
});
</script>