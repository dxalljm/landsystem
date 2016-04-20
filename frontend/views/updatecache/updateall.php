<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Collection */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="collection-form">
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <?= $this->title ?>
                    </h3>
                </div>
                <div class="box-body">

  <h4>此更新在后台执行，您可以做其他操作，过几分钟重新查看图表数据。</h4>
  <script type="text/javascript">
	var result = <?= $result?>
	if(result == 1)
		<?php echo '完成';?>
	else
		<?php echo '正在更新中...';?>
  </script>
              </div>
            </div>
        </div>
    </div>
</section>
</div>