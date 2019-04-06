<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Breedinfo;
use app\models\Theyear;
use app\models\Farms;
use app\models\Breed;
/**
 * breedinfoSearch represents the model behind the search form about `app\models\Breedinfo`.
 */
class breedinfoSearch extends Breedinfo
{
	public $farms_id;
	public $farmer_id;
	public $breedname;
	public $breedaddress;
	public $is_demonstration;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'breed_id', 'number', 'breedtype_id','create_at','update_at','management_area','farms_id','farmer_id','breedname','breedaddress','is_demonstration','farmstate','state','clrate'], 'integer'],
            [['basicinvestment', 'housingarea'], 'number'],
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
    
    public function search($params)
    {
    	
        $query = Breedinfo::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $query->andFilterWhere([
            'id' => $this->id,
            'breed_id' => $this->breed_id,
            'number' => $this->number,
            'basicinvestment' => $this->basicinvestment,
            'housingarea' => $this->housingarea,
            'breedtype_id' => $this->breedtype_id,
        	'management_area' => $this->management_area,
			'farmstate' => $this->farmstate,
        ]);
//        $query->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]]);
        return $dataProvider;
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
//     	var_dump($params);exit;
    	$query = Breedinfo::find();
    
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    	]);
    	if(isset($params['breedinfoSearch']['management_area'])) {
			$this->management_area = $params['breedinfoSearch']['management_area'];
		} else {
			$this->management_area = Farms::getManagementArea()['id'];
		}
		if(count($this->management_area) > 1) {
			$this->management_area = null;
		}
//		var_dump($this->management_area);exit;
    	$farmid = [];
    	if((isset($params['breedinfoSearch']['farms_id']) and $params['breedinfoSearch']['farms_id'] !== '') or (isset($params['breedinfoSearch']['farmer_id']) and $params['breedinfoSearch']['farmer_id'] !== '')) {
    		$farm = Farms::find();
    		$farm->andFilterWhere(['management_area'=>$this->management_area]);
    	}
    	if(isset($params['breedinfoSearch']['farms_id']) and $params['breedinfoSearch']['farms_id'] !== '') {
    		$this->farms_id = $params['breedinfoSearch']['farms_id'];
    		$farm->andFilterWhere($this->pinyinSearch($this->farms_id));
    		 
    	}
    	 
    	if(isset($params['breedinfoSearch']['farmer_id']) and $params['breedinfoSearch']['farmer_id'] !== '') {
    		$this->farmer_id = $params['breedinfoSearch']['farmer_id'];
    		$farm->andFilterWhere($this->farmerpinyinSearch($this->farmer_id));
    	}
    	if(isset($farm)) {
    		foreach ($farm->all() as $value) {
    			$farmid[] = $value['id'];
    		}
    	}
    	$breedid = [];
    	if(isset($params['breedinfoSearch']['is_demonstration']) and $params['breedinfoSearch']['is_demonstration'] !== '') {
    		$this->is_demonstration = $params['breedinfoSearch']['is_demonstration'];
    		$breed = Breed::find();
    		$breed->andFilterWhere(['management_area'=>$this->management_area]);
    		$breed->andFilterWhere(['is_demonstration'=>$this->is_demonstration]);
    	}
    	if(isset($breed)) {
	    	foreach ($breed->all() as $value) {
	    		$breedid[] = $value['id'];
	    	}
    	}
    	if(isset($params['breedinfoSearch']['number']))
    		$query->andFilterWhere($this->numberSearch('number',$params['breedinfoSearch']['number']));
//     		$this->number = $params['breedinfoSearch']['number'];
    	 
    	if(isset($params['breedinfoSearch']['basicinvestment']))
    		$query->andFilterWhere($this->numberSearch('basicinvestment',$params['breedinfoSearch']['basicinvestment']));
    	
    	if(isset($params['breedinfoSearch']['housingarea']))
    		$query->andFilterWhere($this->numberSearch('housingarea',$params['breedinfoSearch']['housingarea']));
    	
    	if(isset($params['breedinfoSearch']['breedtype_id']))
    		$this->breedtype_id = $params['breedinfoSearch']['breedtype_id'];

		if(isset($params['breedinfoSearch']['farmstate']))
			$this->farmstate = $params['breedinfoSearch']['farmstate'];
    	
    	$query->andFilterWhere([
    			'id' => $this->id,
    			'farms_id' => $farmid,
//     			'farmer_id' => $farmid,
    			'breed_id' => $breedid,
//     			'number' => $this->number,
//     			'basicinvestment' => $this->basicinvestment,
//     			'housingarea' => $this->housingarea,
    			'breedtype_id' => $this->breedtype_id,
    			'management_area' => $this->management_area,
				'farmstate' => $this->farmstate,
    	]);
    	$query->andFilterWhere(['between','update_at',$params['begindate'],$params['enddate']]);
//		var_dump($query->where);exit;
    	return $dataProvider;
    }
}
