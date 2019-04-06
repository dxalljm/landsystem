<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%log}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $user_ip
 * @property string $action
 * @property string $action_type
 * @property string $object_name
 * @property string $object_id
 * @property string $operate_desc
 * @property string $operate_time
 * @property string $object_old_attr
 * @property string $object_new_attr
 */
class Log extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'object_id'], 'integer'],
            [['object_old_attr', 'object_new_attr'], 'string'],
            [['user_ip','macadress', 'action', 'action_type', 'object_name', 'operate_desc','class'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'user_id' => '用户ID',
            'user_ip' => '用户IP',
            'macadress' => 'MAC地址',
            'action' => '动作',
            'action_type' => '操作类型',
            'object_name' => '对象名称',
            'object_id' => '被操作对象ID',
            'operate_desc' => '操作内容',
            'operate_time' => '操作时间',
            'object_old_attr' => '对象旧数据',
            'object_new_attr' => '对象新数据',
            'class' => '操作类'
        ];
    }

    public static function getData($model)
    {
        $old = unserialize($model->object_old_attr);
        $new = unserialize($model->object_new_attr);
        $html = '';
        switch ($model->action) {
            case 'basedataverify':
                $class = 'plantingstructurecheck';
                $view = true;
                break;
            case 'site':
                $view = false;
                break;
            default:
                $class = $model->action;
                $view = true;
        }
        if($view) {
            if ($model->class) {
                $classname = $model->class;
            } else {
                $classname = 'app\\models\\' . ucfirst($class);
            }
            $obj = new $classname();
            $attributeLabels = $obj->attributeLabels();
//        var_dump($attributeLabels);exit;
            $html .= '<table class="table table2-bordered">';
            foreach ($attributeLabels as $key => $val) {
                $html .= '<tr>';
                $html .= '<td>' . $val . '</td>';
                if ($old) {
                    if (isset($old[$key])) {
                        $html .= '<td>' . $old[$key] . '</td>';
                    }
                }
                $class = '';
                if ($old and $new) {
                    if (isset($old[$key])) {
                        if (!((string)$old[$key] === (string)$new[$key])) {
                            $class = 'text-red';
                        }
                    }
                }
                if ($new) {
                    if (isset($new[$key])) {
                        $html .= '<td class="' . $class . '">' . $new[$key] . '</td>';
                    }
                }
                $html .= '</tr>';
            }
            $html .= '</table>';
        }
        return $html;
    }
}
