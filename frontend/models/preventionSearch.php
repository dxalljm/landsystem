<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Prevention;
use app\models\Theyear;
use app\models\Farms;
/**
 * preventionSearch represents the model behind the search form about `app\models\Prevention`.
 */
class preventionSearch extends Prevention
{
	public $farmer_id;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'farms_id','farmer_id' ,'breedinfo_id','breedtype_id', 'preventionnumber', 'breedinfonumber', 'isepidemic','management_area'], 'integer'],
            
            [['vaccine','preventionrate', 'isepidemic'], 'safe'],
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
        $query = Prevention::find();

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
            'breedinfo_id' => $this->breedinfo_id,
        	'breedtype_id' => $this->breedtype_id,
            'preventionnumber' => $this->preventionnumber,
            'breedinfonumber' => $this->breedinfonumber,
        	'management_area' => $this->management_area,
            //'preventionrate' => $this->preventionrate,
            //'isepidemic' => $this->isepidemic,
        ]);

        $query->andFilterWhere(['like', 'vaccine', $this->vaccine])
        	->andFilterWhere(['like', 'preventionrate', $this->preventionrate])
       		->andFilterWhere(['like', 'isepidemic', $this->isepidemic])
       		->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]]);
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
    	$query = Prevention::find();
    
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    	]);
    	if(isset($params['preventionSearch']['management_area'])) {
			if ($params['preventionSearch']['management_area'] == 0)
				$this->management_area = NULL;
			else
				$this->management_area = $params['preventionSearch']['management_area'];
		} else {
			$this->management_area = NULL;
		}
    	$farmid = [];
    	if((isset($params['preventionSearch']['farms_id']) and $params['preventionSearch']['farms_id'] !== '') or (isset($params['preventionSearch']['farmer_id']) and $params['preventionSearch']['farmer_id'] !== '')) {
	    	$farm = Farms::find();
	    	$farm->andFilterWhere(['management_area'=>$this->management_area]);
    	}
    	if(isset($params['preventionSearch']['farms_id']) and $params['preventionSearch']['farms_id'] !== '') {
    		$this->farms_id = $params['preventionSearch']['farms_id'];
    		$farm->andFilterWhere($this->pinyinSearch($this->farms_id));
    		    		
    	}

    	if(isset($params['preventionSearch']['farmer_id']) and $params['preventionSearch']['farmer_id'] !== '') {
    		$this->farmer_id = $params['preventionSearch']['farmer_id'];
    		$farm->andFilterWhere($this->farmerpinyinSearch($this->farmer_id));
    	}
    	if(isset($farm)) {
	    	foreach ($farm->all() as $value) {
	    		$farmid[] = $value['id'];
	    	}
    	}
    	if(isset($params['preventionSearch']['breedtype_id']) and $params['preventionSearch']['breedtype_id'] !== '') {
    		$this->breedtype_id = $params['preventionSearch']['breedtype_id'];
    		$query->andFilterWhere(['breedtype_id'=>$this->breedtype_id]);
    	}
    	if(isset($params['preventionSearch']['isepidemic']) and $params['preventionSearch']['isepidemic'] !== '') {
    		$this->isepidemic = $params['preventionSearch']['isepidemic'];
    		$query->andFilterWhere(['isepidemic'=>$this->isepidemic]);
    	}
    	if(isset($params['preventionSearch']['preventionnumber']) and $params['preventionSearch']['preventionnumber'] !== '') {
    		$query->andFilterWhere($this->numberSearch('preventionnumber',$params['preventionSearch']['preventionnumber']));
    	}
    	if(isset($params['preventionSearch']['breedinfonumber']) and $params['preventionSearch']['breedinfonumber'] !== '') {
    		$query->andFilterWhere($this->numberSearch('breedinfonumber',$params['preventionSearch']['breedinfonumber']));
    	}
    	$query->andFilterWhere([
    			'id' => $this->id,
    			'farms_id' => $farmid,
    			'breedinfo_id' => $this->breedinfo_id,
//     			'breedtype_id' => $this->breedtype_id,
//     			'preventionnumber' => $this->preventionnumber,
//     			'breedinfonumber' => $this->breedinfonumber,
    			'management_area' => $this->management_area,
    			//'preventionrate' => $this->preventionrate,
    			//'isepidemic' => $this->isepidemic,
    	]);
    
    	$query->andFilterWhere(['like', 'vaccine', $this->vaccine])
    	->andFilterWhere(['like', 'preventionrate', $this->preventionrate])
    	->andFilterWhere(['like', 'isepidemic', $this->isepidemic])
    	->andFilterWhere(['between','update_at',$params['begindate'],$params['enddate']]);
    	return $dataProvider;
    }
}
