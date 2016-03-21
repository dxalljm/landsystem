<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sales;

/**
 * salesSearch represents the model behind the search form about `app\models\Sales`.
 */
class salesSearch extends Sales
{
	public $farmer_id;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'planting_id','farms_id','plant_id','farmer_id','create_at','update_at','management_area'], 'integer'],
            [['whereabouts'], 'safe'],
            [['volume', 'price'], 'number'],
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
        $query = Sales::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'planting_id' => $this->planting_id,
        	'plant_id' => $this->plant_id,
        	'farms_id' => $this->farms_id,
            'volume' => $this->volume,
            'price' => $this->price,
        	'management_area' => $this->management_area,
        ]);

        $query->andFilterWhere(['like', 'whereabouts', $this->whereabouts]);

        return $dataProvider;
    }
    
    public function pinyinSearch($str = NULL)
    {
    	if (preg_match ("/^[A-Za-z]/", $str)) {
    		$tj = ['like','pinyin',$str];
    	} else {
    		$tj = ['like','farmname',$str];
    	}
    
    	return $tj;
    }
    
    public function farmerpinyinSearch($str = NULL)
    {
    	if (preg_match ("/^[A-Za-z]/", $str)) {
    		$tj = ['like','farmerpinyin',$str];
    	} else {
    		$tj = ['like','farmername',$str];
    	}
    	//     	var_dump($tj);exit;
    	return $tj;
    }
    
    public function searchIndex($params)
    {
//     	var_dump($params);exit;
    	$query = Sales::find();
    
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    	]);
    	if($params['salesSearch']['management_area'] == 0)
    		$this->management_area = NULL;
    	else
    		$this->management_area = $params['salesSearch']['management_area'];
    	$farmid = [];
    	if((isset($params['salesSearch']['farms_id']) and $params['salesSearch']['farms_id'] !== '') or (isset($params['salesSearch']['farmer_id']) and $params['salesSearch']['farmer_id'] !== '')) {
    		$farm = Farms::find();
    		$farm->andFilterWhere(['management_area'=>$this->management_area]);
    	}
    	if(isset($params['salesSearch']['farms_id']) and $params['salesSearch']['farms_id'] !== '') {
    		$this->farms_id = $params['salesSearch']['farms_id'];
    		$farm->andFilterWhere($this->pinyinSearch($this->farms_id));
    	
    	}
    	
    	if(isset($params['salesSearch']['farmer_id']) and $params['salesSearch']['farmer_id'] !== '') {
    		$this->farmer_id = $params['salesSearch']['farmer_id'];
    		$farm->andFilterWhere($this->farmerpinyinSearch($this->farmer_id));
    	}
    	if(isset($farm)) {
    		foreach ($farm->all() as $value) {
    			$farmid[] = $value['id'];
    		}
    	}
    
    	$query->andFilterWhere([
    			'id' => $this->id,
    			'planting_id' => $this->planting_id,
    			'plant_id' => $this->plant_id,
    			'farms_id' => $farmid,
    			'volume' => $this->volume,
    			'price' => $this->price,
    			'management_area' => $this->management_area,
    	]);
    
    	$query->andFilterWhere(['like', 'whereabouts', $this->whereabouts])
    	->andFilterWhere(['between','update_at',$params['begindate'],$params['enddate']]);
    	return $dataProvider;
    }
}
