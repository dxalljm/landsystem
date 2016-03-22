<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fireprevention;
use app\models\Theyear;
use app\models\Farms;
/**
 * firepreventionSearch represents the model behind the search form about `app\models\Fireprevention`.
 */
class firepreventionSearch extends Fireprevention
{
	public $farmer_id;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'farms_id','management_area','farmer_id'], 'integer'],
            [['firecontract', 'safecontract', 'environmental_agreement', 'firetools', 'mechanical_fire_cover', 'chimney_fire_cover', 'isolation_belt', 'propagandist', 'fire_administrator', 'cooker', 'fieldpermit', 'propaganda_firecontract', 'leaflets', 'employee_firecontract', 'rectification_record', 'equipmentpic', 'peoplepic', 'facilitiespic','year'], 'safe'],
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
        $query = Fireprevention::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $query->andFilterWhere([
            'id' => $this->id,
            'farms_id' => $this->farms_id,
        	'management_area' => $this->management_area,
        	'year' => $this->year,
        ]);

        $query->andFilterWhere(['like', 'firecontract', $this->firecontract])
            ->andFilterWhere(['like', 'safecontract', $this->safecontract])
            ->andFilterWhere(['like', 'environmental_agreement', $this->environmental_agreement])
            ->andFilterWhere(['like', 'firetools', $this->firetools])
            ->andFilterWhere(['like', 'mechanical_fire_cover', $this->mechanical_fire_cover])
            ->andFilterWhere(['like', 'chimney_fire_cover', $this->chimney_fire_cover])
            ->andFilterWhere(['like', 'isolation_belt', $this->isolation_belt])
            ->andFilterWhere(['like', 'propagandist', $this->propagandist])
            ->andFilterWhere(['like', 'fire_administrator', $this->fire_administrator])
            ->andFilterWhere(['like', 'cooker', $this->cooker])
            ->andFilterWhere(['like', 'fieldpermit', $this->fieldpermit])
            ->andFilterWhere(['like', 'propaganda_firecontract', $this->propaganda_firecontract])
            ->andFilterWhere(['like', 'leaflets', $this->leaflets])
            ->andFilterWhere(['like', 'employee_firecontract', $this->employee_firecontract])
            ->andFilterWhere(['like', 'rectification_record', $this->rectification_record])
            ->andFilterWhere(['like', 'equipmentpic', $this->equipmentpic])
            ->andFilterWhere(['like', 'peoplepic', $this->peoplepic])
            ->andFilterWhere(['like', 'facilitiespic', $this->facilitiespic])
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
    public function searchindex($params)
    {
//     	    	var_dump($params);exit;
    	$query = Fireprevention::find();
    
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    	]);
    
    	if($params['firepreventionSearch']['management_area'] == 0 or $params['firepreventionSearch']['management_area'] == '')
    		$this->management_area = NULL;
    	else
    		$this->management_area = $params['firepreventionSearch']['management_area'];
    	$farmid = [];
    	if((isset($params['firepreventionSearch']['farms_id']) and $params['firepreventionSearch']['farms_id'] !== '') or (isset($params['firepreventionSearch']['farmer_id']) and $params['firepreventionSearch']['farmer_id'] !== '')) {
	    	$farm = Farms::find();
	    	$farm->andFilterWhere(['management_area'=>$this->management_area]);
    	}
    	if(isset($params['firepreventionSearch']['farms_id']) and $params['firepreventionSearch']['farms_id'] !== '') {
    		$this->farms_id = $params['firepreventionSearch']['farms_id'];
    		$farm->andFilterWhere($this->pinyinSearch($this->farms_id));
    		    		
    	}

    	if(isset($params['firepreventionSearch']['farmer_id']) and $params['firepreventionSearch']['farmer_id'] !== '') {
    		$this->farmer_id = $params['firepreventionSearch']['farmer_id'];
    		$farm->andFilterWhere($this->farmerpinyinSearch($this->farmer_id));
    	}
    	if(isset($farm)) {
	    	foreach ($farm->all() as $value) {
	    		$farmid[] = $value['id'];
	    	}
    	}
    
    	$query->andFilterWhere([
    			'id' => $this->id,
    			'farms_id' => $farmid,
    			'management_area' => $this->management_area,
    	]);

    	$query->andFilterWhere(['between','update_at',$params['begindate'],$params['enddate']]);
    
    	return $dataProvider;
    }
}
