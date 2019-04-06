<?php
/**
 * Created by PhpStorm.
 * User: liujiaming
 * Date: 16/8/19
 * Time: 15:18
 */
namespace frontend\helpers;
use app\models\User;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
class htmlColumn
{
    private static $controller;
    private static $action;
    public static $template = ['view','update','delete'];
    public static $custom;
    //$str为方法名称,array为get参数
    public static function getUrl($array)
    {
        if(!isset($array[0])) {
            $array[0] = self::$controller . '/' . self::$action;
        }
        $url = Url::to($array);
        return $url;
    }

    public static function createButton($str,$array,$t = NULL,$options=[])
    {
        $html = '';
        switch ($str)
        {
            case 'view':
                $title = ['title' => Yii::t('yii', '查看'),'data-pjax' => '0'];
                $span = '<span class="glyphicon glyphicon-eye-open"></span>';
                $url = self::getUrl(['id'=>$array['id']]);
                break;
            case 'update':
                $title = ['title' => Yii::t('yii', '更新'),'data-pjax' => '0'];
                $span = '<span class="glyphicon glyphicon-pencil"></span>';
                $url = self::getUrl($array);
                if(User::disabled()) {
                    $url = '#';
                    $options = ['disabled'=>User::disabled()];
                }
                break;
            case 'delete':
                $title = [
                    'title' => Yii::t('yii', '删除'),
                    'data-pjax' => '0',
                    'data' => [
                        'confirm' => '您确定要删除这项吗？',
                    ],
                ];
                $span = '<span class="glyphicon glyphicon-trash"></span>';
                $url = self::getUrl(['id'=>$array['id'],'farms_id'=>$array['farms_id']]);
                if(User::disabled()) {
                    $url = '#';
                    $options = ['disabled'=>User::disabled()];
                    $title = [
                        'title' => Yii::t('yii', '删除'),
                    ];
                }
                break;
            default:
                $title = ['title' => Yii::t('yii', $t),'data-pjax' => '0'];
                $span = '<span class="btn btn-xs btn-success">'.$t.'</span>';
                $url = self::getUrl($array);
                if(User::disabled()) {
                    $url = '#';
                    $options = ['disabled'=>User::disabled()];
                    $title = [
                        'title' => Yii::t('yii', '删除'),
                    ];
                }
        }
        return Html::a($span,$url, $title,$options);
    }

    public static function show($array,$action = null,$custom = null)
    {
        self::$controller = Yii::$app->controller->id;
        if(User::disabled()) {
            self::$template = ['view'];
        }
        foreach (self::$template as $temp) {

            self::$action = self::$controller.$temp;
            if(empty($action)) {
                $action = self::$action;
            }
            if (\Yii::$app->user->can($action)) {
                echo self::createButton($temp, $array).'&nbsp;';
            }
        }
        if(!empty(self::$custom)) {

        }
    }

    public static function showNorole($array,$controler = null,$acion = null)
    {
        if($controler) {
            self::$controller = $controler;
        }
        else {
            self::$controller = Yii::$app->controller->id;
        }
        if($acion) {
            self::$action = $acion;
        } else {
            self::$action = Yii::$app->controller->action->id;
        }
        foreach (self::$template as $temp) {
            self::$action = self::$controller.$temp;
            if (\Yii::$app->user->can(self::$action)) {
                echo self::createButton($temp, $array).'&nbsp;';
            }
        }
    }

    public static function createButton2($str,$url = '#',$options=null)
    {
        switch ($str)
        {
            case 'view':
                $title = ['title' => Yii::t('yii', '查看'),'data-pjax' => '0'];
                $span = '<span class="glyphicon glyphicon-eye-open"></span>';
                break;
            case 'update':
                $title = ['title' => Yii::t('yii', '更新'),'data-pjax' => '0'];
                $span = '<span class="glyphicon glyphicon-pencil"></span>';
                break;
            case 'delete':
                $title = [
                    'title' => Yii::t('yii', '删除'),
                    'data-pjax' => '0',
                ];
                $options['data'] = ['confirm' => '您确定要删除这项吗？'];
                $span = '<span class="glyphicon glyphicon-trash"></span>';
                break;
        }
        return Html::a($span,$url, $options);
    }

    public static function showButton($options,$url='#')
    {
//        foreach (self::$template as $temp) {
//            self::$action = self::$controller.$temp;
//            if (\Yii::$app->user->can(self::$action)) {
            echo self::createButton2('update',$url,$options).'&nbsp;';
//            }
//        }
    }

    public static function showDeleteButton($url)
    {
//        foreach (self::$template as $temp) {
//            self::$action = self::$controller.$temp;
//            if (\Yii::$app->user->can(self::$action)) {
        echo self::createButton2('delete',$url).'&nbsp;';
//            }
//        }
    }
}
?>