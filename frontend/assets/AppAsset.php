<?php

/**
 * @link http://www.yiiframework.com/
 *
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 *
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    // 注册CSS
    public $css = [
        'css/site.css',
        'vendor/bower/font-awesome/css/font-awesome.min.css',
        'vendor/bower/AdminLTE/dist/css/AdminLTE.min.css',
        'vendor/bower/AdminLTE/dist/css/skins/_all-skins.min.css',
    ];

    // 注册JS
    public $js = [
        'js/main.js',
        //'vendor/bower/AdminLTE/bootstrap/js/bootstrap.min.js',
         'vendor/bower/AdminLTE/dist/js/app.min.js',
    ];

    // 依赖
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    // 定义按需加载css方法，注意加载顺序在最后
    public static function addCss($view, $cssfile) {
        $view->registerCssFile($cssfile, [AppAsset::className(), 'depends' => 'api\assets\AppAsset']);
    }

}
