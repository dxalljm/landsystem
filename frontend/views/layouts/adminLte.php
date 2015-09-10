<?php
use yii\helpers\Html;

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
        <head>
            <meta charset="<?= Yii::$app->charset ?>">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <?= Html::csrfMetaTags() ?>
            <title><?= Html::encode($this->title) ?></title>

            <link rel="stylesheet" href="./vendor/bower/AdminLTE/bootstrap/css/bootstrap.min.css">
            <link rel="stylesheet" href="./vendor/bower/font-awesome/css/font-awesome.min.css">
            <link rel="stylesheet" href="./vendor/bower/AdminLTE/dist/css/AdminLTE.min.css">
            <link rel="stylesheet" href="./vendor/bower/AdminLTE/dist/css/skins/_all-skins.min.css">

        </head>
        <body class="skin-blue sidebar-mini">
            <div class="wrapper">
                <?php $this->beginBody() ?>

                <?= $this->render('//shares/header.php');?>

                <?= $this->render('//shares/sidebar.php');?>

                <div class="wrap">
                    <?= $content ?>
                </div>


                <?php $this->endBody() ?>
            </div>
        </body>
</html>
<?php $this->endPage() ?>
