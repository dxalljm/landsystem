<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Yields;
use app\models\Theyear;
use app\models\Farms;
/**
 * yieldsSearch represents the model behind the search form about `app\models\Yields`.
 */
class yieldsSearch extends Yields
{
	public $farmer_id;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'planting_id', 'farms_id','farmer_id','plant_id','management_area'], 'integer'],
            [['single'], 'number'],
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
        $query = Yields::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $query->andFilterWhere([
            'id' => $this->id,
            'planting_id' => $this->planting_id,
            'farms_id' => $this->farms_id,
            'single' => $this->single,
        	'management_area' => $this->management_area,
        ]);
        $query->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]]);
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
    	$query = Yields::find();
    
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    	]);
    	if(isset($params['yieldsSearch']['management_area'])) {
	    	if($params['yieldsSearch']['management_area'] == 0)
	    		$this->management_area = NULL;
	    	else
	    		$this->management_area = $params['yieldsSearch']['management_area'];
		} else {
			$management_area = Farms::getManagementArea()['id'];
			if(count($management_area) > 1)
				$this->management_area = NULL;
			else 
				$this->management_area = $management_area;
		}
    	$farmid = [];
    	if((isset($params['yieldsSearch']['farms_id']) and $params['yieldsSearch']['farms_id'] !== '') or (isset($params['yieldsSearch']['farmer_id']) and $params['yieldsSearch']['farmer_id'] !== '')) {
	    	$farm = Farms::find();
	    	$farm->andFilterWhere(['management_area'=>$this->management_area]);
    	}
    	if(isset($params['yieldsSearch']['farms_id']) and $params['yieldsSearch']['farms_id'] !== '') {
    		$this->farms_id = $params['yieldsSearch']['farms_id'];
    		$farm->andFilterWhere($this->pinyinSearch($this->farms_id));
    		    		
    	}

    	if(isset($params['yieldsSearch']['farmer_id']) and $params['yieldsSearch']['farmer_id'] !== '') {
    		$this->farmer_id = $params['yieldsSearch']['farmer_id'];
    		$farm->andFilterWhere($this->farmerpinyinSearch($this->farmer_id));
    	}
    	if(isset($farm)) {
	    	foreach ($farm->all() as $value) {
	    		$farmid[] = $value['id'];
	    	}
    	}
    	if(isset($params['yieldsSearch']['plant_id']) and $params['yieldsSearch']['plant_id'] !== '')
    		$this->plant_id = $params['yieldsSearch']['plant_id'];
    	
    	$query->andFilterWhere([
    			'id' => $this->id,
    			'planting_id' => $this->planting_id,
    			'plant_id' => $this->plant_id,
    			'farms_id' => $farmid,
    			'single' => $this->single,
    	]);
    	
    	$query->andFilterWhere(['between','update_at',$params['begindate'],$params['enddate']]);
//     	var_dump($query->where[1]);exit;
    	return $dataProvider;
    }
}
