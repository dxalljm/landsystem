<link rel="stylesheet" href="vendor/bower/viewerjs-master/dist/font-awesome.min.css">
<link rel="stylesheet" href="vendor/bower/viewerjs-master/dist/bootstrap.min.css">
<link rel="stylesheet" href="vendor/bower/viewerjs-master/dist/viewer.css">
<style>
        .pictures {
                margin: 0;
                padding: 0;
                list-style: none;
                max-width: 30rem;
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
<div class="container">
        <div id="galley">
                <ul class="pictures">
                        <?php
                        foreach ($pics as $pic):
                                ?>
                                <li><img data-original="<?= $pic?>" src="<?= $pic?>" alt="<?= $alt?>"></li>
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
