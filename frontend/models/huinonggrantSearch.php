<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Huinonggrant;

/**
 * huinonggrantSearch represents the model behind the search form about `app\models\Huinonggrant`.
 */
class huinonggrantSearch extends Huinonggrant
{
	public $farmer_id;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'farms_id','farmer_id','lease_id','subsidiestype_id','typeid', 'huinong_id', 'state','lease_id','management_area','issubmit'], 'integer'],
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
        	'issubmit' => $this->issubmit,
        ]);

        $query->andFilterWhere(['like', 'note', $this->note]);

        return $dataProvider;
    }
    public function areaSearch($str = NULL)
    {
    	$this->area = $str;
    	if(!empty($this->area)) {
    		preg_match_all('/(.*)([0-9]+?)/iU', $this->area, $where);
    		//print_r($where);
    
    		// 		string(2) ">="
    		// 		string(3) "300"
    		if($where[1][0] == '>' or $where[1][0] == '>=')
    			$tj = ['between', 'area', (float)$where[2][0],(float)99999.0];
    		if($where[1][0] == '<' or $where[1][0] == '<=')
    			$tj = ['between', 'area', (float)0.0,(float)$where[2][0]];
    		if($where[1][0] == '')
    			$tj = ['like', 'area', $this->area];
    	} else
    		$tj = ['like', 'area', $this->area];
    	//var_dump($tj);
    	return $tj;
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
		if(isset($params['state']))
			$this->state = $params['state'];
    	if(isset($params['huinonggrantSearch']['subsidiestype_id']))
    		$this->subsidiestype_id = $params['huinonggrantSearch']['subsidiestype_id'];

    	
    	if(isset($params['huinonggrantSearch']['typeid']))
    		$this->typeid = $params['huinonggrantSearch']['typeid'];
		
    	if(isset($params['huinonggrantSearch']['area']))
    		$query->andFilterWhere($this->areaSearch($params['huinonggrantSearch']['area']));
    
    	$query->andFilterWhere([
    			'id' => $this->id,
    			'farms_id' => $farmid,
    			'lease_id' => $this->lease_id,
    			'huinong_id' => $this->huinong_id,
    			'subsidiestype_id'=>$this->subsidiestype_id,
    			'typeid' => $this->typeid,
    			'management_area' => $this->management_area,
    			'money' => $this->money,
    			'area' => $this->area,
    			'state' => $this->state,
    	]);
    
    	$query->andFilterWhere(['like', 'note', $this->note]);
    
    	return $dataProvider;
    }
}
