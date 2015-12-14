<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%session}}".
 *
 * @property integer $id
 * @property string $key
 * @property string $value
 */
class Search extends \yii\db\ActiveRecord
{
// 	public statc $columns = ['class' => 'yii\grid\SerialColumn'];
	
    
    public static function farms()
    {
    	$columns[] = ['class' => 'yii\grid\SerialColumn'];
    }
}
