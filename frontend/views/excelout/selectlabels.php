<?php
use yii\helpers\Html;
/**
 * Created by PhpStorm.
 * User: liujiaming
 * Date: 2018/3/3
 * Time: 11:32
 */
$this->title = '选择导出项';
//var_dump($labels);
?>
<div class="labels">
    <?= Html::hiddenInput('selected','',['id'=>'Selected','class'=>'form-control'])?>
    <table class="table">
        <tr>
            <td>
                <?php
                $i=1;
                echo '<table class="table">';
                echo '<tr>';
                foreach ($labels as $key => $label) {
//                    var_dump($key);
                    echo '<td><label>'. Html::checkbox($key,false,['class'=>'nodes','value'=>$key]).'<strong>'.$label.'</strong></label></td>';
                    if($i%6 == 0) {
                        echo '</tr>';
                    }
                    $i++;
                }
                echo '</table>';
                ?>
            </td>
        </tr>
        <tr>
            <td>
                <label><input type="checkbox" class="all"/> 全选</label><label><input type="checkbox" class="invert"/> 反选</label><label><input type="checkbox" class="revoke"/> 取消选择 </label>
            </td>
        </tr>
    </table>

</div>
<script>
    selected.all('.all', '.nodes');
    selected.invert('.invert', '.nodes');
    selected.revoke('.revoke', '.nodes');
    $('.nodes').click(function () {
        if ($(this).is(':checked') == true) {
//            $('.nodes').each(function(){
                var select = $('#Selected').val();
                var val = $(this).val();

                if ($(this).is(':checked') == true) {
                    if(select == '') {
                        $('#Selected').val(val);
                    } else {
                        $('#Selected').val(select + ',' + val);
                    }
                }
//            });
        } else {
            var selected = $('#Selected').val();
            var val = $(this).val();
            var selectArray = selected.split(',');
            console.log(selectArray);
            var newArray = new Array();
            $.each(selectArray,function (i,e) {
                if(val != e)
                {
                    newArray[i] = e;
                }
            });
            $('#Selected').val(newArray.join(','));
        }
    });
    $('.all').click(function(){
        if ($(this).is(':checked') == true) {
            var newArray = new Array();
            $('.nodes').each(function(i,e){
                console.log(e.value);
                newArray[i] = e.value;
            });
            $('#Selected').val(newArray.join(','));
        } else {
            $('#Selected').val('');
        }
    });
    $('.invert').click(function(){
        if ($(this).is(':checked') == true) {
            $('#Selected').val('');
            var newArray = new Array();
            $('.nodes').each(function(i,e){
                if ($(this).is(':checked') == true) {
                    newArray[i] = e.value;
                }
            });
            $('#Selected').val(newArray.join(','));
        } else {
            var newArray = new Array();
            var n = 0;
            $('.nodes').each(function(i,e){
                if ($(this).is(':checked') == true) {
                    newArray[n] = e.value;
                    n++;
                }
            });
            $('#Selected').val(newArray.join(','));
        }
    });
    $('.revoke').click(function(){
        $('#Selected').val('');
    });
</script>