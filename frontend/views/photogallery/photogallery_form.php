<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use yii\helpers\Url;
use app\models\Tablefields;
use app\models\Tables;
/* @var $this yii\web\View */
/* @var $model app\models\Photogallery */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="photogallery-form">
<?php var_dump($file);?>
    <?php $form = ActiveFormrdiv::begin(); ?>
<span class="btn btn-success fileinput-button">
    <i class="glyphicon glyphicon-plus"></i>
    <span>请选择...</span>
    <input id="fileupload" type="file" name="upload_file" multiple="">
</span>
<?php echo html::img('')?>
    <?php ActiveFormrdiv::end(); ?>

</div>
<script language="javascript" type="text/javascript">
$(function () {
	var url = "<?= Url::to(['photogallery/fileupload','controller'=>yii::$app->controller->id,'field'=>'picaddress']);?>";
    $('#fileupload').fileupload({
        url: url,
        dataType: 'json',
		done: function (e, data) {
			var url2 = data.result.url;
// 			alert(url2);
			$('img').attr('src', url2);
        }
    });
});
// 	$(function () {
		//var url = "<?//= Url::to(['photogallery/fileupload']);?>";
//         $('#fileupload').fileupload({
//             url: url,
//             dataType: 'json',
// 			done: function (e, data) {
// 				var url2 = data.result.url;
// 				$('img').attr('src', url2);
//             }
//         });
// 	});

</script>