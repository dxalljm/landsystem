<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>图库</title>
<?php
use yii\helpers\Url;
?>
<link rel="stylesheet" href="vendor/bower/viewerjs-master/dist/font-awesome.min.css">
<link rel="stylesheet" href="vendor/bower/viewerjs-master/dist/bootstrap.min.css">
<link rel="stylesheet" href="vendor/bower/viewerjs-master/dist/viewer.css">
<style>
    .pictures {
        margin: 0;
        padding: 0;
        list-style: none;
        max-width: 50rem;
    }

    .pictures > li {
        float: left;
        width: 50%;
        height: 100%;
        margin: 0 -1px -1px 0;
        border: 1px solid transparent;
        overflow: hidden;
    }

    .pictures > li > img {
        width: 100%;
        cursor: -webkit-zoom-in;
        cursor: zoom-in;
    }

    .viewer-download {
        color: #fff;
        font-family: FontAwesome;
        font-size: .75rem;
        line-height: 1.5rem;
        text-align: center;
    }

    .viewer-download::before {
        content: "\f019";
    }
</style>
<link href="vendor/bower/jquery-treemenu/jquery.treemenu.css" rel="stylesheet" type="text/css">
<style>
    *{list-style:none;border:none;}
    body{font-family:Arial;background-color:#2C3E50;}
    .tree {  color:#FFFFFF;width:800px;margin:100px auto;}
    .tree li,
    .tree li > a,
    .tree li > span {
        padding: 4pt;
        border-radius: 4px;
    }

    .tree li a {
        color:#FFFFFF;
        text-decoration: none;
        line-height: 20pt;
        border-radius: 4px;
    }

    .tree li a:hover {
        background-color: #34BC9D;
        color: #fff;
    }

    .active {
        background-color: #34BC9D;
        color: #FFFFFF;
    }

    .active a {
        color: #FFFFFF;
    }

    .tree li a.active:hover {
        background-color: #34BC9D;
    }
</style>
</head>
<body>
<br><br><br><br>
<div class="row">
    <div class="col-md-3">
        <ul class="tree">
            <?php
//            var_dump($tree);exit;
            foreach ($tree as $key1 => $value1) {
                echo '<li><a href="#">'.$key1.'</a>';
                if(is_array($value1)) {
                    echo '<ul>';
                    foreach ($value1 as $key2 => $value2) {
                        if(empty($farms_id)) {
                            echo '<li><a href="'.Url::to(['picturelibrary/showimg','farms_id'=>$farms_id]).'">'.$key2.'</a>';
                            if(is_array($value2)) {
                                echo '<ul>';
                                foreach ($value2['tree'] as $key3 => $value3) {
                                    $path = $value3['path'];

                                    if ($class == $value3['table'] and $field == $value3['field'] and $farms_id == $value2['farms_id']) {
                                        echo '<li><a href="' . \yii\helpers\Url::to(['picturelibrary/showimg', 'farms_id' => $value2['farms_id'], 'class' => $value3['table'], 'field' => $value3['field']]) . '" class="active">' . $path . '</a></li>';
                                        //                                            echo '<li><a href="#" class="active"  onclick=openModal('.$farms_id.',"'.$value3['table'].'","'.$value3['field'][array_search($field,$value3['field'])].'")>' . $value3['path'] . '</a></li>';
                                    } else {
                                        echo '<li><a href="' . \yii\helpers\Url::to(['picturelibrary/showimg', 'farms_id' => $value2['farms_id'], 'class' => $value3['table'], 'field' => $value3['field']]) . '" class="">' . $path . '</a></li>';
                                        //                                            echo '<li><a href="#" onclick=openModal('.$farms_id.',"'.$value3['table'].'","'.$value3['field'][array_search($field,$value3['field'])].'")>' . $value3['path'] . '</a></li>';
                                    }
                                }
                                echo '</li>';
                                echo '</ul>';
                            }
                        } else {
                            $farm = \app\models\Farms::findOne($farms_id);
                            if($key2 == $farm['farmname'].'-'.$farm['farmername']) {
                                echo '<li><a href="'.Url::to(['picturelibrary/showimg','farms_id'=>$value2['farms_id']]).'" class="">' . $key2 . '</a>';
                                if(is_array($value2)) {
                                    echo '<ul>';
                                    foreach ($value2['tree'] as $key3 => $value3) {
                                        $path = $value3['path'];
//                                        var_dump($value3);
//                                        echo '<br><br>';
                                        if ($class == $value3['table'] and $field == $value3['field'] and $farms_id == $value2['farms_id']) {
                                            echo '<li><a href="' . \yii\helpers\Url::to(['picturelibrary/showimg', 'farms_id' => $value2['farms_id'], 'class' => $value3['table'], 'field' => $value3['field']]) . '" class="active">' . $path . '</a></li>';
                                            //                                            echo '<li><a href="#" class="active"  onclick=openModal('.$farms_id.',"'.$value3['table'].'","'.$value3['field'][array_search($field,$value3['field'])].'")>' . $value3['path'] . '</a></li>';
                                        } else {
                                            echo '<li><a href="' . \yii\helpers\Url::to(['picturelibrary/showimg', 'farms_id' => $value2['farms_id'], 'class' => $value3['table'], 'field' => $value3['field']]) . '" class="">' . $path . '</a></li>';
                                            //                                            echo '<li><a href="#" onclick=openModal('.$farms_id.',"'.$value3['table'].'","'.$value3['field'][array_search($field,$value3['field'])].'")>' . $value3['path'] . '</a></li>';
                                        }
                                    }
                                    echo '</li>';
                                    echo '</ul>';
                                }
                            }
                        }


                    }
                    echo '</li>';
                    echo '</ul>';
                }
            }
            ?>
    </div>
    <div class="col-md-9" id="pic">
        <div class="container">
            <div id="galley">
                <ul class="pictures">
                    <?php
//                    var_dump($pics);exit;
                    foreach ($pics['pics'] as $key => $pic):
                        ?>
                        <li><img data-original="<?= $pic?>" src="<?= $pic?>" alt="<?= $pics['alt'][$key]?>"></li>
                        <?php
                    endforeach;
                    ?>
                </ul>
            </div>
        </div>
        <script src="vendor/bower/jquery/dist/jquery-3.2.1.slim.min.js"></script>
        <script src="vendor/bower/AdminLTE/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="vendor/bower/viewerjs-master/dist/viewer.js"></script>
        <script>
            window.addEventListener('DOMContentLoaded', function () {
                var galley = document.getElementById('galley');
                var viewer;
                viewer = new Viewer(galley, {
                    url: 'data-original',
                });
            });
        </script>
    </div>
</div>
</body>
</html>
<script src="vendor/bower/jquery-treemenu/jquery-1.11.2.min.js"></script>
<script src="vendor/bower/jquery-treemenu/jquery.treemenu.js"></script>
<script>
    function openModal(id,classname,field) {
//        $.getJSON('index.php?r=webupload/getpics', {farms_id: id,table:classname,field:field}, function (data) {
////            $('.pictures').html('');
//            var html = '';
//            $.each(data.pics, function(i,val){
//                html += '<li><img data-original="'+val+'" src="'+val+'" alt="'+data.alt+'"></li>';
//            });
//            $('.pictures').html(html);
//        });
        $.get('index.php?r=webupload/showimg', {farms_id: id,table:classname,field:field}, function (data) {
            $('#pic').html(data);
        });
    }
    $(function(){
        $(".tree").treemenu({delay:300}).openActive();
    });
</script>