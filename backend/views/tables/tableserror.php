
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>错误</title>

    <style>
        body {
            font: normal 9pt "Verdana";
            color: #000;
            background: #fff;
        }

        h1 {
            font: normal 18pt "Verdana";
            color: #f00;
            margin-bottom: .5em;
        }

        h2 {
            font: normal 14pt "Verdana";
            color: #800000;
            margin-bottom: .5em;
        }

        h3 {
            font: bold 11pt "Verdana";
        }

        p {
            font: normal 9pt "Verdana";
            color: #000;
        }

        .version {
            color: gray;
            font-size: 8pt;
            border-top: 1px solid #aaa;
            padding-top: 1em;
            margin-bottom: 1em;
        }
    </style>
</head>

<body>
    <h2><?= $message ?></h2>
    <p>
       Web服务器处理您的请求时发生上述错误。
    </p>
    <p>
        如果你认为这是一个服务器错误，请联系我们，谢谢你。
    </p>
    <div class="version">
        <?= date('Y-m-d H:i:s', time()) ?>
    </div>
</body>
</html>
<?php
if (method_exists($this, 'endPage')) {
    $this->endPage();
}
