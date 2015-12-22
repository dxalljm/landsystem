<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Plantingstructure;
use app\models\Theyear;
/**
 * plantingstructureSearch represents the model behind the search form about `app\models\Plantingstructure`.
 */
class plantingstructureSearch extends Plantingstructure
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'plant_id', 'goodseed_id', 'lease_id', 'farms_id','management_area'], 'integer'],
            [['area'], 'number'],
            [['zongdi'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
//     	var_dump($params);exit;
        $query = Plantingstructure::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
		
        $this->load($params);

        $query->andFilterWhere([
            'id' => $this->id,
            'plant_id' => $this->plant_id,
            'area' => $this->area,
            'goodseed_id' => $this->goodseed_id,
        	'lease_id' => $this->lease_id,
            'farms_id' => $this->farms_id,
        	'management_area'=>$this->management_area,
        ]);

        $query->andFilterWhere(['like', 'zongdi', $this->zongdi])
        ->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]]);

        return $dataProvider;
    }
    public function searchIndex($params)
    {
//     	 var_dump($params);exit;
    	$query = Plantingstructure::find();
    
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    	]);
    	if(isset($params['farms_id']))
    		$farms_id = $params['farms_id'];
    	else
    		$farms_id = $this->farms_id;
    	
    	if(isset($params['plant_id']))
    		$plant_id = $params['plant_id'];
    	else
    		$plant_id = $this->plant_id;
    	
    	if(isset($params['goodseed_id']))
    		$goodseed_id = $params['goodseed_id'];
    	else
    		$goodseed_id = $this->goodseed_id;
    	//         $this->setAttributes($params);
    
    	$query->andFilterWhere([
    			'id' => $this->id,
    			'plant_id' => $plant_id,
    			'area' => $this->area,
    			'goodseed_id' => $goodseed_id,
    			'lease_id' => $this->lease_id,
    			'farms_id' => $farms_id,
    	]);
    
    	$query->andFilterWhere(['like', 'zongdi', $this->zongdi])
    	->andFilterWhere(['between','update_at',$params['begindate'],$params['enddate']]);
    
    	return $dataProvider;
    }
}
