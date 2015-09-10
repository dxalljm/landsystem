<?php
    use yii\helpers\Html;
    use frontend\assets\AppAsset;

    AppAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
  <html lang="<?= Yii::$app->language ?>">
  <head>
    <meta charset="<?= Yii::$app->charset ?>">
    <title><?= Html::encode($this->title) ?></title>

    <?php $this->head(); ?>

    <?= Html::csrfMetaTags(); ?>


  </head>
  <body class="skin-blue sidebar-mini">

    <div class="wrapper">

      <?php $this->beginBody() ?>

        <!-- 头部 -->
        <?= $this->render('//shares/header.php');?>

        <!-- 左侧菜单 -->
        <?= $this->render('//shares/sidebar.php');?>

        <!-- 内容区域 -->
        <div class="content-wrapper">
          <?= $content ?>
        </div>

      <?php $this->endBody() ?>

    </div>

  </body>
</html>
<?php $this->endPage() ?>
