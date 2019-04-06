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
<link rel="stylesheet" type="text/css" href="cssLoading/css/normalize.css" />
<link rel="stylesheet" type="text/css" href="cssLoading/css/default.css">
<link href="cssLoading/css/Icomoon/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="cssLoading/js/main.js"></script>
<style>
    li{
        width:auto;margin:0px 0px;;
    }
</style>
<style>
    #loading{
        /*background-color: #ECECEC;*/
        height: 100%;
        width: 100%;
        position: fixed;
        z-index: 1500;
        margin-top: 0px;
        top: 0px;
        display: none;
        /*opacity:0.7;*/
    }
    #loading-center{
        width: 100%;
        height: 100%;
        position: relative;
    }
    #loading-center-absolute {
        position: absolute;
        left: 50%;
        top: 50%;
        height: 50px;
        width: 150px;
        margin-top: -25px;
        margin-left: -75px;

    }
    .object{
        width: 8px;
        height: 50px;
        margin-right:5px;
        background-color: #008776;
        -webkit-animation: animate 1s infinite;
        animation: animate 1s infinite;
        float: left;
        opacity:1;
    }

    .object:last-child {
        margin-right: 0px;
    }

    .object:nth-child(10){
        -webkit-animation-delay: 0.9s;
        animation-delay: 0.9s;
    }
    .object:nth-child(9){
        -webkit-animation-delay: 0.8s;
        animation-delay: 0.8s;
    }
    .object:nth-child(8){
        -webkit-animation-delay: 0.7s;
        animation-delay: 0.7s;
    }
    .object:nth-child(7){
        -webkit-animation-delay: 0.6s;
        animation-delay: 0.6s;
    }
    .object:nth-child(6){
        -webkit-animation-delay: 0.5s;
        animation-delay: 0.5s;
    }
    .object:nth-child(5){
        -webkit-animation-delay: 0.4s;
        animation-delay: 0.4s;
    }
    .object:nth-child(4){
        -webkit-animation-delay: 0.3s;
        animation-delay: 0.3s;
    }
    .object:nth-child(3){
        -webkit-animation-delay: 0.2s;
        animation-delay: 0.2s;
    }
    .object:nth-child(2){
        -webkit-animation-delay: 0.1s;
        animation-delay: 0.1s;
    }
    @-webkit-keyframes animate {

        50% {
            -ms-transform: scaleY(0);
            -webkit-transform: scaleY(0);
            transform: scaleY(0);

        }
    }
    @keyframes animate {
        50% {
            -ms-transform: scaleY(0);
            -webkit-transform: scaleY(0);
            transform: scaleY(0);
        }
    }
</style>
<header class="main-header">



    <nav class="navbar navbar-static-top" role="navigation" style="background-color: #008776">
        <a href="#" class="sidebar-toggle" data-toggle="" >

        </a>
        <div class="text text-white">
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
                    <a href="#" class="dropdown-toggle" style="color:#FFFFFF" data-toggle="dropdown" id="all">任务<span class="notification allcount">0</span><span class="caret"></span></a>

                    <ul class="dropdown-menu" role="menu">
                        <li><a href="<?= Url::to(['reviewprocess/reviewprocessindex'])?>" id="two">审核任务<span class="notification count2">0</span></a></li>
                        <li><a href="<?= Url::to(['reviewprocess/reviewprocesswait'])?>" id="zero">待办任务<span class="notification count0">0</span></a></li>
                        <li><a href="<?= Url::to(['reviewprocess/reviewprocessing'])?>" id="four">正在办理<span class="notificationgreen count4">0</span></a></li>
                        <li><a href="<?= Url::to(['reviewprocess/reviewprocessfinished'])?>" id="six">已完成任务<span class="notificationgreen count6">0</span></a></li>
                        <li><a href="<?= Url::to(['reviewprocess/reviewprocessreturn'])?>" id="eight">退回任务<span class="notification count8">0</span></a></li>
                        <li><a href="<?= Url::to(['reviewprocess/reviewprocesscacle'])?>" id="nine">撤消任务<span class="notificationgreen count9">0</span></a></li>
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
                        <li class="divider"></li>
                        <li><a href="<?= Url::to('index.php?r=insurancetype/insurancetypeindex')?>">保险种类管理</a></li>
                        <li class="divider"></li>
                        <li><a href="<?= Url::to('index.php?r=fixedtype/fixedtypeindex')?>">固定资产类别管理</a></li>
                    </ul>

                    <?php } else {
                        if($menu['menuname'] !== '任务') {
                            echo '<li width="auto" style="color:#FFFFFF"><a href="' . Url::to('index.php?r=' . $menu['menuurl']) . '">' . $menu['menuname'];
                        }
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
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">任务<span class="notification allcount">0</span><span class="caret"></span></a>

                    <ul class="dropdown-menu" role="menu">
                        <li><a href="<?= Url::to(['reviewprocess/reviewprocessindex'])?>" id="two">审核任务<span class="notification count2">0</span></a></li>
                        <li><a href="<?= Url::to(['reviewprocess/reviewprocesswait'])?>" id="zero">待办任务<span class="notification count0">0</span></a></li>
                        <li><a href="<?= Url::to(['reviewprocess/reviewprocessing'])?>" id="four">正在办理<span class="notificationgreen count4">0</span></a></li>
                        <li><a href="<?= Url::to(['reviewprocess/reviewprocessfinished'])?>" id="six">已完成任务<span class="notificationgreen count6">0</span></a></li>
                        <li><a href="<?= Url::to(['reviewprocess/reviewprocessreturn'])?>" id="eight">退回任务<span class="notification count8">0</span></a></li>
                        <li><a href="<?= Url::to(['reviewprocess/reviewprocesscacle'])?>" id="nine">撤消任务<span class="notificationgreen count9">0</span></a></li>
                    </ul>
                    <?php }
                    if(isset($_GET['farms_id'])) {
                        echo '<li>'.Html::a('图库','#',['onclick'=>"javascript:window.open('".Url::to(['picturelibrary/showimg','farms_id'=>$_GET['farms_id']])."','','width=1200,height=800,top=250,left=380, location=no, toolbar=no, status=no, menubar=no, resizable=no, scrollbars=yes');return false;"]).'</li>';
                    } else {
                        echo '<li>'.Html::a('图库','#',['onclick'=>"javascript:window.open('".Url::to(['picturelibrary/showimg'])."','','width=1200,height=800,top=250,left=380, location=no, toolbar=no, status=no, menubar=no, resizable=no, scrollbars=yes');return false;"]).'</li>';
                    }
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
                        <a href="<?= Url::to(['verisonlist/list'])?>">版本：V<?= \app\models\Verison::findOne(1)->ver?></a>
                        <!--                        <a href="javascript:void(0);" id="currentTime">--><?//= date('Y年m月j日')?><!--</a>-->
                    </li>

                </ul>
            </div>
        </div>
    </nav>
</header>
<div id="loading">
    <div id="loading-center">
        <div id="loading-center-absolute">
            <div class="object"></div>
            <div class="object"></div>
            <div class="object"></div>
            <div class="object"></div>
            <div class="object"></div>
            <div class="object"></div>
            <div class="object"></div>
            <div class="object"></div>
            <div class="object"></div>
            <div class="object"></div>
        </div>
    </div>
</div>
<aside class="control-sidebar control-sidebar-dark"  data-active-color="sgreen" data-background-color="green" data-image="images/sidebar-1.jpg">

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
        <div id="control-sidebar-theme-demo-options-tab" class="tab-pane active" style="color: #000000"  data-image="images/sidebar-2.jpg">
            <div>
                <h4 class="control-sidebar-heading text-center" style="color: #000000">首页图表设置</h4>
                <?php $form = ActiveFormrdiv::begin(['action'=>Url::to(['user/userindexecharts','user_id'=>Yii::$app->getUser()->id])]); ?>
                <div class="form-group">
                    <label class="control-sidebar-subheading">
                        <table style="color: #000000">
                            <tr class="text-center">
                                <td width="60%">第一行中间</td>
                                <td><?= $form->field($indexechartsModel,'onem')->dropDownList($echartsPlate,['prompt'=>'不显示'])->error(false)->label(false)?></td>
                            </tr>
                        </table>
                    </label>
                </div>
                <div class="form-group">
                    <label class="control-sidebar-subheading">
                        <table style="color: #000000">
                            <tr class="text-center">
                                <td width="60%">第一行右侧</td>
                                <td><?= $form->field($indexechartsModel,'oner')->dropDownList($echartsPlate,['prompt'=>'不显示'])->error(false)->label(false)?></td>
                            </tr>
                        </table>
                    </label>
                </div>
                <div class="form-group">
                    <label class="control-sidebar-subheading">
                        <table style="color: #000000">
                            <tr class="text-center">
                                <td width="60%">第二行左侧</td>
                                <td><?= $form->field($indexechartsModel,'twol')->dropDownList($echartsPlate,['prompt'=>'不显示'])->error(false)->label(false)?></td>
                            </tr>
                        </table>
                    </label>
                </div>
                <div class="form-group">
                    <label class="control-sidebar-subheading">
                        <table style="color: #000000">
                            <tr class="text-center">
                                <td width="60%">第二行中间</td>
                                <td><?= $form->field($indexechartsModel,'twom')->dropDownList($echartsPlate,['prompt'=>'不显示'])->error(false)->label(false)?></td>
                            </tr>
                        </table>
                    </label>
                </div>
                <div class="form-group">
                    <label class="control-sidebar-subheading">
                        <table style="color: #000000">
                            <tr class="text-center">
                                <td width="60%">第二行右侧</td>
                                <td><?= $form->field($indexechartsModel,'twor')->dropDownList($echartsPlate,['prompt'=>'不显示'])->error(false)->label(false)?></td>
                            </tr>
                        </table>
                    </label>
                </div>
                <div class="form-group">
                    <?= Html::submitButton('更新', ['class' => 'btn btn-success']) ?>
                </div>
                <?php ActiveFormrdiv::end(); ?>
            </div>
        </div>
        <!-- /.tab-pane -->
    </div>
</aside>
<script>
    $( "#picture").dialog({
        autoOpen: false,
        width: 1000,
        open: function (event, ui) {
            $(".ui-dialog-titlebar-close", $(this).parent()).hide();
        }
    });
    window.onbeforeunload=function (){
        $("#loading").show();
    //	alert("===onbeforeunload===");
//        var action = "<?//= Yii::$app->controller->action->id?>//";
//    	if(event.clientX>document.body.clientWidth && event.clientY < 0 || event.altKey){
    //		alert("你关闭了浏览器");
//    	}else{
//            if(action !== 'basedataverifylist')
//            {

//            }
//    	}
    }
    $(document).ready(function () {
        $.getJSON('index.php?r=search/getprocesscount', {id: 'all'}, function (data) {
            if(data.data)
                $('.allcount').html(data.data);
            else {
                $('#all').html('任务<span class="caret"></span>');
            }
        });
        $.getJSON('index.php?r=search/getprocesscount', {id: 0}, function (data) {
            if(data.data)
                $('.count0').html(data.data);
            else {
                $('#zero').html('待办任务');
            }
        });
        $.getJSON('index.php?r=search/getprocesscount', {id: 2}, function (data) {
            if(data.data) {
                $('.count2').html(data.data);
            } else {
                $('#two').html('审核任务');
            }
        });
        $.getJSON('index.php?r=search/getprocesscount', {id: 4}, function (data) {
            if(data.data) {
                $('.count4').html(data.data);
            } else {
                $('#four').html('正在办理');
            }
        });
        $.getJSON('index.php?r=search/getprocesscount', {id:6}, function (data) {
            if(data.data) {
                $('.count6').html(data.data);
            } else {
                $('#six').text('已完成任务');
            }
        });
        $.getJSON('index.php?r=search/getprocesscount', {id: 8}, function (data) {
            if(data.data) {
                $('.count8').html(data.data);
            } else {
                $('#eight').html('退回任务');
            }
        });
        $.getJSON('index.php?r=search/getprocesscount', {id: 9}, function (data) {
            if(data.data) {
                $('.count9').html(data.data);
            } else {
                $('#nine').html('撤消任务');
            }
        });
    });
</script>