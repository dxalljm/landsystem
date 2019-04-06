<?php 
use yii\helpers\Url;
?>
     <!-- 头部 -->
        <?= $this->render('//shares/header.php');?>

        <!-- 左侧菜单 -->
        <?= $this->render('//shares/sidebar.php');?>

        <!-- 内容区域 -->
        <div class="content-wrapper">
          <?= $content ?>
        </div>
