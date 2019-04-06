    <link rel="stylesheet" href="vendor/bower/viewerjs-master/dist/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/bower/viewerjs-master/dist/viewer.css">
    <style>
        .pictures {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .pictures > li {
            float: left;
            width: 33.3%;
            height: 33.3%;
            margin: 0 -1px -1px 0;
            border: 1px solid transparent;
            overflow: hidden;
        }

        .pictures > li > img {
            width: 100%;
            cursor: -webkit-zoom-in;
            cursor: zoom-in;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="vendor/bower/jquerytree/css/bootstrap.min.css" />
    <link href="vendor/bower/jquerytree/css/style.css" rel="stylesheet" type="text/css">
    <script src="vendor/bower/jquerytree/js/jquery-1.7.2.min.js"></script>
<div class="row">
    <div class="col-md-3">
        <div class="tree well">
        <ul>
            <?php
            foreach ($tree as $key1 => $value1) {
                echo '<li><span><i class="icon-folder-open"></i> '.$key1.'</span><a href="#"></a>';
                if(is_array($value1)) {
                    echo '<ul>';
                    foreach ($value1 as $key2 => $value2) {
                        if(empty($farms_id)) {
                            echo '<li><span><i class="icon-minus-sign"></i> '.$key2.'</span><a href="#"></a>';
                            if(is_array($value2)) {
                                echo '<ul>';
                                foreach ($value2 as $key3 => $value3) {
                                    echo '<li><span><i class="icon-leaf"></i>'.$value3['path'].'</span><a href="#"></a></li>';
                                }
                                echo '</li>';
                                echo '</ul>';
                            }
                        } else {
                            $farm = \app\models\Farms::findOne($farms_id);
                            if($key2 == $farm['farmname'].'-'.$farm['farmername']) {
                                echo '<li><span><i class="icon-minus-sign"></i> '.$key2.'</span><a href="#"></a>';
                                if(is_array($value2)) {
                                    echo '<ul>';
                                    foreach ($value2 as $key3 => $value3) {
                                        if($class == $value3['table'] and in_array($field, $value3['field'])) {
                                            echo '<li><span><i class="icon-leaf"></i> '.$value3['path'].'</span><a href="#" class="active"  data-target="#modal" data-toggle="modal" onclick=openModal('.$farms_id.',"'.$value3['table'].'","'.$value3['field'][array_search($field,$value3['field'])].'")></a></li>';
                                        } else {
                                            echo '<li><span><i class="icon-leaf"></i> '.$value3['path'].'</span><a href="#" data-target="#modal" data-toggle="modal" onclick=openModal('.$farms_id.',"'.$value3['table'].'","'.$value3['field'][array_search($field,$value3['field'])].')"></a></li>';
                                        }
                                    }
                                    echo '</li>';
                                    echo '</ul>';
                                }
                            }
//                            else {
//                                echo '<li><a href="#">'.$key2.'</a>';
//                                if(is_array($value2)) {
//                                    echo '<ul>';
//                                    foreach ($value2 as $key3 => $value3) {
//                                        echo '<li><a href="#">'.$value3['path'].'</a></li>';
//                                    }
//                                    echo '</li>';
//                                    echo '</ul>';
//                                }
//                            }

                        }


                    }
                    echo '</li>';
                    echo '</ul>';
                }
            }
            ?>
        </div>
    </div>
    <div class="col-md-9">
        <div class="container">
            <button type="button" class="btn btn-primary" data-target="#modal" data-toggle="modal">
                Launch the demo
            </button>
            <!-- Modal -->
            <div class="modal fade" id="modal" role="dialog" aria-labelledby="modalLabel" tabindex="-1">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalLabel">Viewer</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <script src="vendor/bower/jquery/dist/jquery-3.2.1.slim.min.js"></script>
<script src="vendor/bower/AdminLTE/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="vendor/bower/viewerjs-master/dist/viewer.js"></script>
<script>
    function openModal(id,classname,field) {
        $.get('index.php?r=webupload/getpics', {farms_id: id,table:classname,field:field}, function (body) {
            $('.modal-body').html(body);
        });
    }
    window.addEventListener('DOMContentLoaded', function () {
        var galley = document.getElementById('galley');
        var viewer;

        $('#modal').on('shown.bs.modal', function (e) {
            // WARNING: should ignore Viewer's `shown` event here.
            if(e.namespace === 'bs.modal') {
                viewer = new Viewer(galley, {
                    url: 'data-original',
                });
            }
        }).on('hidden.bs.modal', function (e) {
            // WARNING: should ignore Viewer's `hidden` event here.
            if(e.namespace === 'bs.modal') {
                viewer.destroy();
            }
        });
    });
</script>

<!--    <script src="vendor/bower/jquery-treemenu/jquery.treemenu.js"></script>-->
    <script>
        $(function(){
            $(".tree").treemenu({delay:300}).openActive();
        });
    </script>