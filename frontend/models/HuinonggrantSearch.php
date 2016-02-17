<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Huinonggrant;

/**
 * HuinonggrantSearch represents the model behind the search form about `app\models\Huinonggrant`.
 */
class HuinonggrantSearch extends Huinonggrant
{
	public $farmer_id;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'farms_id','farmer_id','lease_id', 'huinong_id', 'state','lease_id','management_area'], 'integer'],
            [['money', 'area'], 'number'],
            [['note'], 'safe'],
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
        $query = Huinonggrant::find();

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
            'farms_id' => $this->farms_id,
        	'lease_id' => $this->lease_id,
            'huinong_id' => $this->huinong_id,
        	'management_area' => $this->management_area,
            'money' => $this->money,
            'area' => $this->area,
            'state' => $this->state,
        ]);

        $query->andFilterWhere(['like', 'note', $this->note]);

        return $dataProvider;
    }
    public function searchIndex($params)
    {
    	$query = Huinonggrant::find();
    
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    	]);
    
    	if($params['huinonggrantSearch']['management_area'] == 0)
    		$this->management_area = NULL;
    	else
    		$this->management_area = $params['huinonggrantSearch']['management_area'];
    	$farmid = [];
    	if((isset($params['huinonggrantSearch']['farms_id']) and $params['huinonggrantSearch']['farms_id'] !== '') or (isset($params['huinonggrantSearch']['farmer_id']) and $params['huinonggrantSearch']['farmer_id'] !== '')) {
	    	$farm = Farms::find();
	    	$farm->andFilterWhere(['management_area'=>$this->management_area]);
    	}
    	if(isset($params['huinonggrantSearch']['farms_id']) and $params['huinonggrantSearch']['farms_id'] !== '') {
    		$this->farms_id = $params['huinonggrantSearch']['farms_id'];
    		$farm->andFilterWhere($this->pinyinSearch($this->farms_id));
    		    		
    	}

    	if(isset($params['huinonggrantSearch']['farmer_id']) and $params['huinonggrantSearch']['farmer_id'] !== '') {
    		$this->farmer_id = $params['huinonggrantSearch']['farmer_id'];
    		$farm->andFilterWhere($this->farmerpinyinSearch($this->farmer_id));
    	}
    	if(isset($farm)) {
	    	foreach ($farm->all() as $value) {
	    		$farmid[] = $value['id'];
	    	}
    	}
//     	var_dump($farmid);exit;
    	if(isset($params['huinonggrantSearch']['plant_id']))
    		$this->plant_id = $params['huinonggrantSearch']['plant_id'];

    	
    	if(isset($params['huinonggrantSearch']['goodseed_id']))
    		$this->goodseed_id = $params['huinonggrantSearch']['goodseed_id'];
		
    	if(isset($params['huinonggrantSearch']['area']))
    		$query->andFilterWhere($this->areaSearch($params['huinonggrantSearch']['area']));
    
    	$query->andFilterWhere([
    			'id' => $this->id,
    			'farms_id' => $this->farms_id,
    			'lease_id' => $this->lease_id,
    			'huinong_id' => $this->huinong_id,
    			'management_area' => $this->management_area,
    			'money' => $this->money,
    			'area' => $this->area,
    			'state' => $this->state,
    	]);
    
    	$query->andFilterWhere(['like', 'note', $this->note]);
    
    	return $dataProvider;
    }
}
