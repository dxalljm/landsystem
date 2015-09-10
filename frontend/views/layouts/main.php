<?php
    use yii\helpers\Html;
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
  <html lang="<?= Yii::$app->language ?>">
  <head>
    <meta charset="<?= Yii::$app->charset ?>">
    <title><?= Html::encode($this->title) ?></title>

    <!-- AdminLTE样式文件 -->
    <link rel="stylesheet" href="./vendor/bower/AdminLTE/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="./vendor/bower/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="./vendor/bower/AdminLTE/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="./vendor/bower/AdminLTE/dist/css/skins/_all-skins.min.css">

    <?= Html::csrfMetaTags() ?>
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
