<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sales;
use app\models\Farms;

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
            [['id', 'planting_id','farms_id','plant_id','farmer_id','create_at','update_at','management_area','state'], 'integer'],
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
//		var_dump($params);exit;
        $query = Sales::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

		if(isset($params['salesSearch']['management_area'])) {
			if($params['salesSearch']['management_area'] == 0)
				$this->management_area = NULL;
			else
				$this->management_area = $params['salesSearch']['management_area'];
		} else {
			$management_area = Farms::getManagementArea()['id'];

			if(count($management_area) > 1)
				$this->management_area = NULL;
			else
				$this->management_area = $management_area;
		}

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
			'state' => $this->state,
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
    	if(isset($params['salesSearch']['management_area'])) {
	    	if($params['salesSearch']['management_area'] == 0)
	    		$this->management_area = NULL;
	    	else
	    		$this->management_area = $params['salesSearch']['management_area'];
		} else {
			$management_area = Farms::getManagementArea()['id'];
			if(count($management_area) > 1)
				$this->management_area = NULL;
			else 
				$this->management_area = $management_area;
		}
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
		if(isset($params['salesSearch']['whereabouts']) and $params['salesSearch']['whereabouts'] !== '') {
			$this->whereabouts = $params['salesSearch']['whereabouts'];
		}
		if(isset($params['salesSearch']['year']) and $params['salesSearch']['year'] !== '') {
			$this->year = $params['salesSearch']['year'];
		}
    	$query->andFilterWhere([
    			'id' => $this->id,
    			'planting_id' => $this->planting_id,
    			'plant_id' => $this->plant_id,
    			'farms_id' => $farmid,
    			'volume' => $this->volume,
    			'price' => $this->price,
    			'management_area' => $this->management_area,
				'whereabouts' => $this->whereabouts,
				'year' => $this->year,
    	]);
    
    	//$query->andFilterWhere(['between','update_at',$params['begindate'],$params['enddate']]);
    	return $dataProvider;
    }

	public function searchSearch($params)
	{
//     	var_dump($params);exit;
		$query = Sales::find();

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);
		if(isset($params['salesSearch']['management_area'])) {
			if($params['salesSearch']['management_area'] == 0)
				$this->management_area = NULL;
			else
				$this->management_area = $params['salesSearch']['management_area'];
		} else {
			$management_area = Farms::getManagementArea()['id'];
			if(count($management_area) > 1)
				$this->management_area = NULL;
			else
				$this->management_area = $management_area;
		}
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
		if(isset($params['salesSearch']['whereabouts']) and $params['salesSearch']['whereabouts'] !== '') {
			$this->whereabouts = $params['salesSearch']['whereabouts'];
		}
		if(isset($params['salesSearch']['year']) and $params['salesSearch']['year'] !== '') {
			$this->year = $params['salesSearch']['year'];
		}
		$query->andFilterWhere([
			'id' => $this->id,
			'planting_id' => $this->planting_id,
			'plant_id' => $this->plant_id,
			'farms_id' => $farmid,
			'volume' => $this->volume,
			'price' => $this->price,
			'management_area' => $this->management_area,
			'whereabouts' => $this->whereabouts,
			'year' => $this->year,
		]);

		$query->andFilterWhere(['between','update_at',$params['begindate'],$params['enddate']]);
//		var_dump($query->where);exit;
//		var_dump($dataProvider->getModels());exit;
		return $dataProvider;
	}
}
