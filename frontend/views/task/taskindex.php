<?php
namespace frontend\controllers;
use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use dosamigos\datetimepicker\DateTimePicker;
use frontend\helpers\ActiveFormrdiv;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\breedSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '完成任务情况';
?>
<div class="breed-index">

    <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <?php User::tableBegin($this->title);?>
            <?php $form = ActiveFormrdiv::begin(['method'=>'get']); ?>
            <table class="table table-hover">
                <tr>
                    <td align="right">日期</td><?php //var_dump(MenuToUser::getUserSearch());exit;?>
                    <td align="right">自</td>
                    <td><?php echo DateTimePicker::widget([
                            'name' => 'begindate',
                            'language' => 'zh-CN',
                            'value' => date('Y-m-d',$begindate),
                            'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
                            'options' => [
                                'readonly' => true
                            ],
                            'clientOptions' => [

                                'format' => 'yyyy-mm-dd',
                                'todayHighlight' => true,
                                'autoclose' => true,
                                'minView' => 2,
                                'maxView' => 4,
                            ]
                        ]);?></td>
                    <td>至</td>
                    <td><?php echo DateTimePicker::widget([
                            'name' => 'enddate',
                            'language' => 'zh-CN',
                            'value' => date('Y-m-d',$enddate),
                            'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
                            //'type' => DatePicker::TYPE_COMPONENT_APPEND,
                            'options' => [
                                'readonly' => true
                            ],
                            'clientOptions' => [
                                'language' => 'zh-CN',
                                'format' => 'yyyy-mm-dd',
                                //'todayHighlight' => true,
                                'autoclose' => true,
                                'minView' => 2,
                                'maxView' => 4,
                            ]
                        ]);?></td>
                    <td>止</td>
                    <td><?= html::submitButton('查询',['class'=>'btn btn-success','id'=>'searchButton'])?></td>
                </tr>
            </table>
            <?php ActiveFormrdiv::end(); ?>
            <?php  User::tableEnd();?>
        </div>
    </div>

    <?php 
        echo $html;

    ?>
            

    </section>
</div>
    <div class="row" id="dialogMsg">
        <div class="col-xs-12">
            <?php User::tableBegin($this->title);?>
            <div id="info"></div>
            <?php  User::tableEnd();?>
        </div>
    </div>
<script>
    function showDialog(id)
    {
        $.get('index.php?r=task/getinfo', {id:id,begindate:'<?= $begindate?>',enddate:'<?= $enddate?>'}, function (body) {
            $('#info').html(body);
            $( "#dialogMsg" ).dialog( "open" );
        });
        $("[data-toggle='popover']").popover();
    }
    $( "#dialogMsg" ).dialog({
        autoOpen: false,
        width: 1000,
        title : "详细信息",  //弹出框标题
//        position : "center",  //窗口显示的位置
//    show: "blind",
//    hide: "explode",
        modal: true,//设置背景灰的
        position: { using:function(pos){
            console.log(pos)
            var topOffset = $(this).css(pos).offset().top;
            if (topOffset = 0||topOffset>0) {
                $(this).css('top', 80);
            }
            var leftOffset = $(this).css(pos).offset().left;
            if (leftOffset = 0||leftOffset>0) {
                $(this).css('left', 560);
            }
        }},
    });
</script>