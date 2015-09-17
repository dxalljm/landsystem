<?php

namespace app\models;

use Yii;
use app\models\Farms;
/**
 * This is the model class for table "{{%collection}}".
 *
 * @property integer $id
 * @property string $payyear
 * @property integer $farms_id
 * @property string $billingtime
 * @property integer $amounts_receivable
 * @property integer $real_income_amount
 * @property integer $ypayyear
 * @property integer $ypayarea
 * @property integer $ypaymoney
 * @property integer $owe
 */
class Collection extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	
    public static function tableName()
    {
        return '{{%collection}}';
    }

    /**
     * @inheritdoc
     */
	public function rules() 
    { 
        return [
            [['farms_id', 'amounts_receivable', 'real_income_amount', 'ypayyear', 'isupdate'], 'integer'],
            [['ypayarea', 'ypaymoney', 'owe'], 'number'],
            [['payyear', 'billingtime','cardid'], 'string', 'max' => 500]
        ]; 
    } 

    public function getAR($year)  //当年应收金额
    {
    	
    	$farm = Farms::find()->where(['id'=>$_GET['farmsid']])->one();
    	$plantprice = PlantPrice::find()->where(['years'=>$year])->one();
    	$result = $farm['measure']*30*$plantprice['price'];
    	return $result;
    }
    
    public function getYpayarea($year,$real_income_amount)   //应追缴面积
    {
    	$plantprice = PlantPrice::find()->where(['years'=>$year])->one();
    	$result = sprintf("%.2f", ($this->getAR($year)-$real_income_amount)/30/$plantprice['price']);
    	return $result;
    }
    
    public function getYpaymoney($year,$real_income_amount)  	//应追缴金额
    {

    		return $this->getAR($year)-$real_income_amount;
    }
    
    public function getOwe($cardid,$farmsid,$year)   //剩余欠缴金额
    {
    	$result = 0;
    	$collections = Collection::find()->where(['farms_id'=>$farmsid,'cardid'=>$cardid])->andWhere('ypayyear<'.$year)->all();
    	//print_r($collections);
    	foreach($collections as $val){
    		$result+=$val['ypaymoney'];
    	}
    	return $result;
    }
    /** 
     * @inheritdoc 
     */ 
    public function attributeLabels() 
    { 
        return [ 
            'id' => 'ID',
            'payyear' => '缴费年度',
            'farms_id' => '农场ID',
            'billingtime' => '开票时间',
            'amounts_receivable' => '应收金额',
            'real_income_amount' => '实收金额',
            'ypayyear' => '应缴费年度',
            'ypayarea' => '应追缴费面积',
            'ypaymoney' => '应追缴费金额',
            'owe' => '剩余欠缴金额',
            'isupdate' => '是否可更新',
        	'cardid' => '法人身份证',
        	'create_at' => '创建日期',
        	'update_at' => '更新日期',
        ]; 
    } 
    public function getfarms()
    {
    	return $this->hasOne(Farms::className(), ['id' => 'farms_id']);
    }
}
