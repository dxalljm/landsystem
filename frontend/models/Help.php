<?php

namespace app\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%help}}".
 *
 * @property integer $id
 * @property string $mark
 * @property string $content
 * @property string $title
 */
class Help extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%help}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['mark', 'title','number'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'mark' => '标识',
            'content' => '备注',
            'title' => '标题',
            'number' => '标题序号',
        ];
    }

    public static function showHelp($mark)
    {
        $html = '';
//        $circle = '<small class="label pull-right bg-red"><i class="fa fa-question-circle"></i></small>';

//        var_dump($help->content);
//        $html .= '<div class="container" style="padding: 30px 30px 10px;" >';

        $html .= '<small class="label pull-right"><i class="fa fa-question-circle"></i></small>';

//        $html .= '</div>';

        return $html;
    }

    public static function showHelp2($mark)
    {
        $help = self::find()->where(['mark'=>$mark])->one();
        $html = '';
        $html .= '<span class="input-group-btn">';
        $html .= '<button type="button" class="btn btn-info btn-flat" title="'.$help->title.'" data-container="body" data-toggle="popover" data-placement="bottom" data-content="'.$help->content.'">';
        $html .= self::showHelp($mark);
        $html .= '</button>';
        $html .= '</span>';
        return $html;
    }

    public static function showHelp3($str,$mark)
    {
        $help = self::find()->where(['mark' => $mark])->one();
        $html = $str;
        if($help) {
            $html .= '<span title="' . $help->title . '" data-container="body" data-toggle="popover" data-placement="bottom" data-content="' . $help->content . '">';
            $html .= '<i class="fa fa-question-circle text-aqua"></i>';
            $html .= '</span>';
        }
        return $html;
    }

    public static function showHelp4($mark)
    {
        $help = self::find()->where(['mark'=>$mark])->one();
        $html = '';
        if($help) {
            $html .= '<span class="input-group-btn" id="help4" title="' . $help->title . '" data-container="body" data-toggle="popover" data-placement="bottom" data-content="' . $help->content . '">';
            $html .= '<div class="badge bg-aqua"><i class="fa fa-question-circle"></i></div>';
            $html .= '</span>';
            $html .= '</div>';
            return $html;
        } else
            return '';
    }

    public static function showHelp5($mark)
    {
        $html = '';
        $html .= '<span class="input-group-btn">';
        $html .= '<a class="tooltip" href="/vendor/bower/tooltip/scr/helptips.php?mark='.$mark.'" onmouseover="tooltip.ajax(this, "/vendor/bower/tooltip/scr/helptips.php?mark='.$mark.'");" onclick="return false;">';
//        $html .= '<div class="badge bg-aqua"><i class="fa fa-question-circle"></i></div>';
        $html .= '???';
        $html .= '</a>';
        $html .= '</span>';
        $html .= '</div>';
        return $html;
    }

    public static function dialogDiv()
    {
        $html = '';
        $html .= '<div id="helpdialog" title="'.$help->title.'">';
        $html .= '<table width=100%>';
        $html .= '<tr>';
        $html .= '<td>'.$help->content.'</td>';
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '</div>';
        return $html;
    }

    public static function showDialog($mark)
    {
//        var_dump($mark);
        $html = '';
        $help = self::find()->where(['mark'=>$mark])->one();
        $html .= '<a class="input-group-btn" onclick="openHelpdialog()">';
        $html .= '<div class="badge bg-aqua"><i class="fa fa-question-circle"></i></div>';
        $html .= '</a>';
        $html .= '</div>';
        $html .= '<div id="helpdialog" title="'.$help->title.'">';
        $html .= '<table width=100%>';
        $html .= '<tr>';
        $html .= '<td>'.$help->content.'</td>';
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '</div>';
        return $html;
    }
    
    public static function phones()
    {
        $html = '';
        if(isset($_GET['farms_id']) and User::getItemname('地产科')) {
            $farm = Farms::findOne($_GET['farms_id']);
            $html .= '<span class="pull-right" title="联系他" data-container="body" data-toggle="popover" data-placement="bottom" data-content="' . $farm->farmername.':'.$farm->telephone . '">';
            $html .= '<i class="fa fa-phone text-aqua"></i>';
            $html .= '</span>';
        }
        return $html;
    }
}
