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
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li >
                    <a href="<?= Url::to('index.php?r=site/index')?>">首页</a>
                </li>

                <?php
                if(yii::$app->user->identity->username != 'admin') {
                    $menuliststr = MenuToUser::find()->where(['user_id'=>\Yii::$app->user->id])->one()['menulist'];
                    $menulistarr = explode(',', $menuliststr);

                    foreach($menulistarr as $val) {
                        $menu = Mainmenu::find()->where(['id'=>$val])->one();
                        echo "<li ><a href=" . Url::to('index.php?r='.$menu['menuurl']) . ">". $menu['menuname'] . "</a></li>";
                    }
                }
                ?>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">数据管理 <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="<?= Url::to('index.php?r=nation/nationindex')?>">民族管理</a></li>
                        <li class="divider"></li>
                        <li><a href="<?= Url::to('index.php?r=plant/plantindex')?>">作物管理</a></li>
                        <li class="divider"></li>
                        <li><a href="<?= Url::to('index.php?r=Inputproduct/Inputproductindex')?>">投入品管理</a></li>
                        <li class="divider"></li>
                        <li><a href="<?= Url::to('index.php?r=pesticides/pesticidesindex')?>">农药管理</a></li>
                        <li class="divider"></li>
                        <li><a href="<?= Url::to('index.php?r=goodseed/goodseedindex')?>">良种管理</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>