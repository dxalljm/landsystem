<?php
use app\models\MenuToUser;
use app\models\Mainmenu;
use yii\helpers\Url;
?>

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
            <?php if(Yii::$app->user->identity->username !== 'cwk01') {?>
                <li >
                    <a href="<?= Url::to('index.php?r=site/index')?>">首页</a>
                </li>
			<?php }?>
                <?php
                if(yii::$app->user->identity->username != 'admin') {
                    $menuliststr = MenuToUser::find()->where(['user_id'=>\Yii::$app->user->id])->one()['menulist'];
                    $menulistarr = explode(',', $menuliststr);

                    foreach($menulistarr as $val) {
                        $menu = Mainmenu::find()->where(['id'=>$val])->one();
                        if($menu['menuurl'] == 'dropdown') {?>
                        	<li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">数据管理 <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="<?= Url::to('index.php?r=nation/nationindex')?>">民族管理</a></li>
                        <li class="divider"></li>
                        <li><a href="<?= Url::to('index.php?r=plant/plantindex')?>">作物管理</a></li>
                        <li class="divider"></li>
                        <li><a href="<?= Url::to('index.php?r=inputproduct/inputproductindex')?>">投入品管理</a></li>
                        <li class="divider"></li>
                        <li><a href="<?= Url::to('index.php?r=inputproductbrandmodel/inputproductbrandmodelindex')?>">投入品品牌型号</a></li>
                        <li class="divider"></li>
                        <li><a href="<?= Url::to('index.php?r=pesticides/pesticidesindex')?>">农药管理</a></li>
                        <li class="divider"></li>
                        <li><a href="<?= Url::to('index.php?r=goodseed/goodseedindex')?>">良种管理</a></li>
                        <li class="divider"></li>
                        <li><a href="<?= Url::to('index.php?r=cooperative/cooperativeindex')?>">合作社管理</a></li>
                        <li class="divider"></li>
                        <li><a href="<?= Url::to('index.php?r=disputetype/disputetypeindex')?>">纠纷类型</a></li>
                        <li class="divider"></li>
                        <li><a href="<?= Url::to('index.php?r=breedtype/breedtypeindex')?>">养殖种类</a></li>
                        
                    </ul>
                        <?php } else {
                        echo "<li ><a href=" . Url::to('index.php?r='.$menu['menuurl']) . ">". $menu['menuname'] . "</a></li>";
                    } ?>
                    
               
                <?php }} ?>

                
            </ul>
        </div>
    </nav>
</header>