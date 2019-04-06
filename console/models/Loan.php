<?php

namespace console\models;

use Yii;
use yii\helpers\Html;
/**
 * This is the model class for table "{{%loan}}".
 *
 * @property integer $id
 * @property integer $farms_id
 * @property double $mortgagearea
 * @property string $mortgagebank
 * @property double $mortgagemoney
 * @property string $mortgagetimelimit
 */
class Loan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%loan}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farms_id','management_area','state','reviewprocess_id','auditprocess_id'], 'integer'],
            [['mortgagearea', 'mortgagemoney', 'create_at','update_at'], 'number'],
            [['mortgagebank','begindate','enddate'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'farms_id' => '农场ID',
        	'management_area' => '管理区',
            'mortgagearea' => '抵押面积',
            'mortgagebank' => '抵押银行',
            'mortgagemoney' => '贷款金额',
            'begindate' => '开始日期',
        	'enddate' => '结束日期',
        	'create_at' => '创建日期',
        	'update_at' => '更新日期',
        	'state' => '状态',
        	'reviewprocess_id' => '审核ID',
            'auditprocess_id' => '审核流程ID'
        ];
    }
    
    public static function getBankName()
    {
        return [
            '中国建设银行'=>'中国建设银行',
            '中国工商银行'=>'中国工商银行',
            '中国银行'=>'中国银行',
            '中国农业银行'=>'中国农业银行',
            '大兴安岭农村商业银行'=>'大兴安岭农村商业银行',
            '龙江银行'=>'龙江银行',
            '邮政储蓄'=>'邮政储蓄',
        ];
    }
    public static function getBankList($user_id)
    {
    	//     	$typename = [];
    	$bankname = [];
    	$loans = Loan::find()->where(['management_area'=>Farms::getUserManagementArea($user_id)])->andFilterWhere(['between','create_at',Theyear::getYeartime($user_id)[0],Theyear::getYeartime($user_id)[1]])->all();
    	//     	var_dump($loans);
    	foreach ($loans as $loan) {
    		$bankname[] = $loan->mortgagebank;
    		//     		var_dump($tyepname);
    	}
    	 
    	$result = array_unique($bankname);
    	//     	var_dump($result);
    	return $result;
    }
    public static function getOneBank($id)
    {
    	return self::getBankName()[$id];
    }

    public static function showReviewprocess($field,$process)
    {
        $html = '';
        $fromundo = '';
        $classname = 'app\\models\\' . ucfirst($field);
        $classdata = new $classname();
        $lists = $classname::loanAttributesList();
//        var_dump($lists);exit;
        if($lists) {
            $html .= '<td colspan="7" align="left">';
            $html .= '<table class="table">';
            foreach ($lists as $key => $list) {
                $html .= '<tr>';
                $html .= '<td width="100">';

                $html .= Html::radioList($key, $classdata[$key], ['否', '是'], ['onChange' => 'showContent("' . $key . '","' . $field . '","' . $classdata[$key . 'content'] . '")', 'class' => 'radiolist']);
                $html .= '</td>';
                $html .= '<td colspan="1" align="left" width="40%">';
                $html .= '&nbsp;&nbsp;' . $list;
                $html .= '</td>';
                $html .= '<td id="' . $key . '-add" colspan="1">';
                if (Reviewprocess::yesOrNo($classdata[$key], $key)) {
                    $html .= Html::textarea($key . 'content', $classdata[$key . 'content'], ['id' => $key . 'content', 'rows' => 1, 'cols' => 50, 'class' => "isText form-control"]);
                }
                $html .= "</td>";
                $html .= '</tr>';
            }
//            $html .= '<tr>';
//            $html .= '<td width="150">';
//            $html .= Html::radioList($field . 'isAgree', '', [1 => '同意', 0 => '不同意'], ['onChange' => 'showContent("' . $field . 'isAgree' . '","' . $field . '","' . $field . 'content' . '")', 'class' => 'radiolist' . $field]);
//            $html .= '</td>';
//            $html .= '<td align="right" width="1%" id="' . $field . 'isAgree' . '-text">';
//            $html .= '</td>';
////            $html .= '<td>';
////            $html .= Html::dropDownList($field .'undo', '', Reviewprocess::showProccessList($process), ['class' => 'form-control', 'id' => $field .'Undo']);
////            $html .= '</td>';
//            $html .= '<td width="50%" id="' . $field . 'isAgree' . '-add">';
//            $html .= "</td>";
//            $html .= '</tr>';
            $html .= '</table>';
            $html .= '</td>';
        }

        return $html;
    }

    public static function getLoancache($user_id)
    {
        $result = [];
        $money = 0.0;
        $area = Farms::getUserManagementArea($user_id);
        $bank = self::getBankList($user_id);
        
        foreach ( $area as $key => $value ) {
            $areaMoney = [];
            foreach ($bank as $val) {
//             	var_dump($val);
                $money = Loan::find()->where(['management_area'=>$value,'mortgagebank'=>$val,'lock'=>1])->andFilterWhere(['between','create_at',Theyear::getYeartime($user_id)[0],Theyear::getYeartime($user_id)[1]])->sum('mortgagemoney');
                if($money)
                	$areaMoney[] = (float)sprintf("%.2f", $money);
            }
//             var_dump($areaMoney);
            $result[] = [
                'name' => str_ireplace('管理区', '', ManagementArea::find()->where(['id'=>$value])->one()['areaname']),
                'type' => 'bar',
                'stack' => $value,
                'data' => $areaMoney,
            ];
        }
        
//     	var_dump($result);
        $jsonData = json_encode ($result);

        return $jsonData;
    }
    public static function getLoanMoney($user_id)
    {
    	return Loan::find()->where(['management_area'=>Farms::getUserManagementArea($user_id),'lock'=>1])->andFilterWhere(['between','create_at',Theyear::getYeartime($user_id)[0],Theyear::getYeartime($user_id)[1]])->sum('mortgagemoney');
    }
}
