<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="images/apple-icon.png" />
    <link rel="icon" type="image/png" href="images/favicon.png" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>岭南管委会</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />

    <!-- Bootstrap core CSS     -->
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <!--  Material Dashboard CSS    -->
    <link href="css/material-dashboard.css" rel="stylesheet" />
    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="css/demo.css" rel="stylesheet" />
    <!--     Fonts and icons     -->
    <link href="css/demo-documentation.css" rel="stylesheet" />
<!--    <script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>-->
    <style>
        pre.prettyprint {
            background-color: #eee;
            border: 0px;
            margin-bottom: 60px;
            margin-top: 30px;
            padding: 20px;
            text-align: left;
        }

        .atv,
        .str {
            color: #05AE0E;
        }

        .tag,
        .pln,
        .kwd {
            color: #3472F7;
        }

        .atn {
            color: #2C93FF;
        }

        .pln {
            color: #333;
        }

        .com {
            color: #999;
        }

        .space-top {
            margin-top: 50px;
        }

        .area-line {
            border: 1px solid #999;
            border-left: 0;
            border-right: 0;
            color: #666;
            display: block;
            margin-top: 20px;
            padding: 8px 0;
            text-align: center;
        }

        .area-line a {
            color: #666;
        }

        .container-fluid {
            padding-right: 15px;
            padding-left: 15px;
        }
        #loginform-username {
            color: #FFF;
        }
        #loginform-password {
            color: #FFF;
        }
    </style>
    <!--     Fonts and icons     -->
<!--    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">-->
<!--    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons" />-->
</head>

<body class="components-page">

<?php
$img = 'images/loginback/'.rand(1,66).'.jpg';
?>
<div class="page-header header-filter" style="background-image: url(<?= $img?>);">
    <div class="container">
        <div class="content-center">
            <span class="title text-center" style="font-size: 80px">岭南农业数字化管理平台</span>
            <h3 class="category">V<?= \app\models\Verison::findOne(1)->ver?></h3>
            <h4 class="description text-center">欢迎您使用岭南农业数字化管理平台</h4>
            <?= $content;?>
        </div>
    </div>
</div>

<!-- end section -->
</div>
<footer class="footer footer-transparent">
    <div class="container">

        <div class="copyright">
            &copy;
            <script>
                document.write(new Date().getFullYear())
            </script> 大兴安岭佳明计算机网络科技有限责任公司
        </div>
    </div>
</footer>
</body>
</html>
