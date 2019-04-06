<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\models\groups;
/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <div class="wrap">
        <?php
            NavBar::begin([
                //'brandLabel' => 'My Company',
                //'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            $menuItems = [
//                 ['label' => '首页', 'url' => ['/site/index']],
            ];
            if (Yii::$app->user->isGuest) {
                $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
            } else {
				$menuItems[] = ['label' => '用户管理', 'url' => ['/user/userindex']];
				//$menuItems[] = ['label' => '业务管理', 'url' => ['/business/businessindex']];
				$menuItems[] = ['label' => '菜单管理', 'url' => ['/mainmenu/mainmenuindex']];
				$menuItems[] = ['label' => '导航管理', 'url' => ['/menutouser/menutouserindex']];
				$menuItems[] = ['label' => '数据库表管理', 'url' => ['/tables/tablesindex']];
				$menuItems[] = ['label' => '科室管理', 'url' => ['/department/departmentindex']];
                $menuItems[] = ['label' => '职级管理', 'url' => ['/userlevel/userlevelindex']];
				$menuItems[] = ['label' => '生成数据文件', 'url' => ['/gii']];
//				$menuItems[] = ['label' => '角色管理', 'url' => ['/role/roleindex']];
//				$menuItems[] = ['label' => '权限管理', 'url' => ['/permission/permissionindex']];
				$menuItems[] = ['label' => '流程项目', 'url' => ['/processname/processnameindex']];
				$menuItems[] = ['label' => '审核过程', 'url' => ['/auditprocess/auditprocessindex']];
				$menuItems[] = ['label' => '流程指向', 'url' => ['/logicalpoint/logicalpointindex']];
                $menuItems[] = [
                    'label' => '退出 (' . Yii::$app->user->identity->username . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ];
            }
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $menuItems,
            ]);
            NavBar::end();
        ?>

        <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>
        <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
