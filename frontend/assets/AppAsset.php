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
    public $jsOptions = ['position'=>\yii\web\View::POS_HEAD];

    // 注册CSS
    public $css = [
        'vendor/bower/blueimp-file-upload/css/jquery.fileupload.css',
        'vendor/bower/blueimp-file-upload/css/jquery.fileupload-ui.css',
        'vendor/bower/font-awesome/css/font-awesome.min.css',
        'vendor/bower/font-awesome/css/font-awesome.min.css',
    	'vendor/bower/AdminLTE/plugins/select2/select2.min.css',
        'vendor/bower/AdminLTE/dist/css/AdminLTE.css',
        'vendor/bower/AdminLTE/dist/css/skins/_all-skins.min.css',
    	'vendor/bower/AdminLTE/dist/css/ionicons.min.css',
//        'vendor/bower/AdminLTE/dist/css/material-dashboard.css',
//    	'vendor/bower/nprogress/nprogress.css',
        'vendor/bower/grumble/css/grumble.min.css',
//        'vendor/bower/tooltip/themes/2/tooltip.css',
//        'vendor/bower/jQuery-tips/css/jq22.css',
        'css/site.css',
    	'vendor/bower/jquery-ui-1.11.4/jquery-ui.css',
    ];

    // 注册JS
    public $js = [
    		
        'js/date.js',
        'js/selected.js',
//     	'js/jquery.imgbox.pack.js',
         'vendor/bower/blueimp-file-upload/js/vendor/jquery.ui.widget.js',

        'vendor/bower/AdminLTE/bootstrap/js/bootstrap.min.js',

    	'vendor/bower/jquery-ui-1.11.4/jquery-ui.js',
    	'js/jquerysession.js',
        'js/main.js',
    	'vendor/bower/blueimp-file-upload/js/jquery.fileupload.js',
        'vendor/bower/blueimp-file-upload/js/jquery.fileupload-process.js',
        'vendor/bower/blueimp-file-upload/js/jquery.fileupload-validate.js',
        'vendor/bower/AdminLTE/dist/js/app.min.js',
        'vendor/bower/AdminLTE/plugins/select2/select2.full.min.js',
    	'vendor/bower/devbridge-autocomplete/dist/jquery.autocomplete.min.js',
        'vendor/bower/grumble/js/jquery.grumble.min.js',
    	'js/vendor/bower/lodop/LodopFuncs.js',
//        'vendor/bower/tooltip/themes/2/tooltip.js',
//         'vendor/bower/nprogress/nprogress.js',
        'vendor/bower/echarts/build/dist/echarts.js',
    	'vendor/bower/echarts/build/dist/echarts.min.js',
    		

    	'js/showEcharts.js',
//     	'vendor/bower/jquery-ui-1.11.4/external/jquery/jquery.js',
    	
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
