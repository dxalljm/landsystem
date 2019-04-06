<?php
    use yii\helpers\Html;
    use frontend\assets\AppAsset;
    AppAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
  <html lang="<?= Yii::$app->language ?>">
  <head>
    <meta charset="<?= Yii::$app->charset ?>">
    <title><?= Html::encode($this->title) ?></title>

    <?php $this->head(); ?>

    <?= Html::csrfMetaTags(); ?>

<style>
        html, body {
            min-height: 100%;
            padding: 0;
            margin: 0;
            font-family: 'Source Sans Pro', "Helvetica Neue", Helvetica, Arial, sans-serif;
        }
        iframe {
            display: block;
            overflow: auto;
            border: 0;
            margin: 0;
            padding: 0;
            margin: 0 auto;
        }
        .frame {
            height: 49px;
            margin: 0;
            padding: 0;
            border-bottom: 1px solid #ddd;
        }
        .frame a {
            color: #666;
        }
        .frame a:hover {
            color: #222;
        }
        .frame .buttons a {
            height: 49px;
            line-height: 49px;
            display: inline-block;
            text-align: center;
            width: 50px;
            border-left: 1px solid #ddd;
        }
        .frame .brand {
            color: #444;
            font-size: 20px;
            line-height: 49px;
            display: inline-block;
            padding-left: 10px;
        }
        .frame .brand small {
            font-size: 14px;
        }
        a,a:hover {
            text-decoration: none;
        }
        .container-fluid {
            padding: 0;
            margin: 0;
        }
        .text-muted {
            color: #999;
        }
        .ad {
            text-align: center;
            position: fixed;
            bottom: 0;
            left: 0;
            background: #222;
            background: rgba(0,0,0,0.8);
            width: 100%;
            color: #fff;
            display: none;
        }
        #close-ad {
            float: left;
            margin-left: 10px;
            margin-top: 10px;
            cursor: pointer;
        }
    </style>
    <script>
        (function(i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function() {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                    m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

        ga('create', 'UA-46680343-1', 'almsaeedstudio.com');
        ga('send', 'pageview');

    </script>
  </head>
  <body>
    
  <div class="fixed">

      <?php $this->beginBody() ?>
<iframe src="<?= $this->render('//layouts/mainiframe.php',['content'=>$content])?>" id="preview-iframe"></iframe>
       

      <?php $this->endBody() ?>

    </div>
  </body>
</html>
<script>
    $(function() {
        function _fix() {
            var h = $(window).height();
            var w = $(window).width();
            $("#preview-iframe").css({
                width: w + "px",
                height: (h - 50) + "px"
            });
        }
        _fix();
        $(window).resize(function() {
            _fix();
        });
        $('[data-toggle="tooltip"]').tooltip();

        function iframe_width(width) {
            $("#preview-iframe").animate({width: width}, 500);
        }

        $("#display-full").click(function(e){
            e.preventDefault();
            iframe_width("100%");
        });

        $("#display-940").click(function(e){
            e.preventDefault();
            iframe_width("940px");
        });

        $("#display-480").click(function(e){
            e.preventDefault();
            iframe_width("480px");
        });

        $("#remove-frame").click(function(e){
            e.preventDefault();
            window.location.href = "<?= $this->render('//layouts/mainiframe.php',['content'=>$content])?>";
        });

    });

</script>
<?php $this->endPage() ?>
