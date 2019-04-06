<?php
    use yii\helpers\Html;
    use frontend\assets\AppAsset;
    use frontend\assets\AppAsset2018;
switch(Yii::$app->user->identity->template) {
  case 'default':
    AppAsset::register($this);
    break;
  case 'template2018':
    AppAsset2018::register($this);
    break;
}

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
  <html lang="<?= Yii::$app->language ?>" class="perfect-scrollbar-off">
  <head>
    <meta charset="<?= Yii::$app->charset ?>">
    <title><?= Html::encode($this->title) ?></title>

    <?php $this->head(); ?>

    <?= Html::csrfMetaTags(); ?>


  </head>
  <?php
  switch(Yii::$app->user->identity->template) {
    case 'default':
//      if(Yii::$app->controller->action->id == 'basedataverifylist') {
//        echo '<body class="skin-blue sidebar-collapse sidebar-mini">';
//      } else {
        echo '<body class="skin-blue sidebar-mini">';
//      }
      echo '<div class="fixed">';
      break;
    case 'template2018':
//      if(Yii::$app->controller->action->id == 'basedataverifylist') {
//        echo '<body class="bootstrap-collapse perfect-scrollbar-off sidebar-mini">';
//      } else {
        echo '<body class="bootstrap-collapse perfect-scrollbar-off" height="100%">';
//      }
      echo '<app-my-app ng-version="4.3.1">';
      echo '<router-outlet></router-outlet>';
      echo '<app-layout>';
      echo '<div class="wrapper">';

      break;
  }

  ?>




      <?php $this->beginBody() ?>

        <!-- 头部 -->
      <?php
      switch(Yii::$app->user->identity->template) {
        case 'default':
          echo $this->render('//shares/header.php');
          echo $this->render('//shares/sidebar.php');
          echo '<div class="content-wrapper">';
          echo $content;
          echo '</div>';
          break;
        case 'template2018':
          echo $this->render('//shares/header2018.php');
          echo $this->render('//shares/sidebar2018.php');
//          echo '<app-regular-table-cmp>';
          echo '<div class="main-panel">';
          echo $content;
          echo '</div>';
          echo '</div>';
          echo '</app-layout>';
          break;
      }
      ?>


      <?php $this->endBody() ?>

    </div>

  </body>
</html>
<?php $this->endPage() ?>
