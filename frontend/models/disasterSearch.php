<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Disaster;
use app\models\Theyear;
use app\models\Farms;
/**
 * disasterSearch represents the model behind the search form about `app\models\Disaster`.
 */
class disasterSearch extends Disaster
{
	public $farmer_id;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'farms_id', 'disastertype_id','farmer_id','isinsurance','management_area'], 'integer'],
            [['disasterarea', 'insurancearea', 'yieldreduction', 'socmoney'], 'number'],
            [['disasterplant'], 'safe'],
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
        $query = Disaster::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $query->andFilterWhere([
            'id' => $this->id,
            'farms_id' => $this->farms_id,
            'disastertype_id' => $this->disastertype_id,
            'disasterarea' => $this->disasterarea,
            'insurancearea' => $this->insurancearea,
            'yieldreduction' => $this->yieldreduction,
            'socmoney' => $this->socmoney,
        	'isinsurance' => $this->isinsurance,
        	'management_area' => $this->management_area,
        ]);

        $query->andFilterWhere(['like', 'disasterplant', $this->disasterplant])
        ->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]]);

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
    public function numberSearch($field,$str = NULL)
    {
    	$this->$field = $str;
    	if(!empty($this->$field)) {
    		preg_match_all('/(.*)([0-9]+?)/iU', $this->$field, $where);
    		if($where[1][0] == '>' or $where[1][0] == '>=')
    			$tj = ['between', $field, (float)$where[2][0],(float)99999.0];
    		if($where[1][0] == '<' or $where[1][0] == '<=')
    			$tj = ['between', $field, (float)0.0,(float)$where[2][0]];
    		if($where[1][0] == '')
    			$tj = ['like', $field, $this->$field];
    	} else
    		$tj = ['like', $field, $this->$field];
    	return $tj;
    }
    public function searchIndex($params)
    {
//     	var_dump($params);
    	$query = Disaster::find();
    
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    	]);
    	if(isset($params['disasterSearch']['management_area'])) {
	    	if($params['disasterSearch']['management_area'] == 0)
	    		$this->management_area = NULL;
	    	else
	    		$this->management_area = $params['disasterSearch']['management_area'];
		} else {
			$management_area = Farms::getManagementArea()['id'];
			if(count($management_area) > 1)
				$this->management_area = NULL;
			else 
				$this->management_area = $management_area;
		}
    	$farmid = [];
    	if((isset($params['disasterSearch']['farms_id']) and $params['disasterSearch']['farms_id'] !== '') or (isset($params['disasterSearch']['farmer_id']) and $params['disasterSearch']['farmer_id'] !== '')) {
	    	$farm = Farms::find();
	    	$farm->andFilterWhere(['management_area'=>$this->management_area]);
    	}
    	if(isset($params['disasterSearch']['farms_id']) and $params['disasterSearch']['farms_id'] !== '') {
    		$this->farms_id = $params['disasterSearch']['farms_id'];
    		$farm->andFilterWhere($this->pinyinSearch($this->farms_id));
    		    		
    	}

    	if(isset($params['disasterSearch']['farmer_id']) and $params['disasterSearch']['farmer_id'] !== '') {
    		$this->farmer_id = $params['disasterSearch']['farmer_id'];
    		$farm->andFilterWhere($this->farmerpinyinSearch($this->farmer_id));
    	}
    	if(isset($farm)) {
	    	foreach ($farm->all() as $value) {
	    		$farmid[] = $value['id'];
	    	}
    	}
    	if(isset($params['disasterSearch']['isinsurance']) and $params['disasterSearch']['isinsurance'] !== '') {
    		$this->isinsurance = $params['disasterSearch']['isinsurance'];
    	}
    	
    	if(isset($params['disasterSearch']['disastertype_id']) and $params['disasterSearch']['disastertype_id'] !== '') {
    		$this->disastertype_id = $params['disasterSearch']['disastertype_id'];
    	}
    	
    	if(isset($params['disasterSearch']['disasterplant']) and $params['disasterSearch']['disasterplant'] !== '') {
    		$this->disasterplant = $params['disasterSearch']['disasterplant'];
    	}
    	
    	if(isset($params['disasterSearch']['disasterarea']) and $params['disasterSearch']['disasterarea'] !== '') {
    		$query->andFilterWhere($this->numberSearch('disasterarea',$params['disasterSearch']['disasterarea']));
    	}
// var_dump($this->management_area);exit;
    	$query->andFilterWhere([
    			'id' => $this->id,
    			'farms_id' => $farmid,
    			'disastertype_id' => $this->disastertype_id,
    			'management_area' => $this->management_area,
    			'insurancearea' => $this->insurancearea,
    			'disasterplant' => $this->disasterplant,
    			'yieldreduction' => $this->yieldreduction,
    			'socmoney' => $this->socmoney,
    			'isinsurance' => $this->isinsurance,
    	]);
    
    	$query->andFilterWhere(['between','update_at',$params['begindate'],$params['enddate']]);
    
    	return $dataProvider;
    }
}
