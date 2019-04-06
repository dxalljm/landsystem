<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Plantingstructurecheck;
use app\models\Theyear;
use app\models\Farms;
use yii\helpers\ArrayHelper;
use app\models\Goodseed;
use app\models\Plant;
use app\models\Huinong;
use app\models\User;
/**
 * plantingstructurecheckSearch represents the model behind the search form about `app\models\plantingstructurecheck`.
 */
class plantingstructurecheckSearch extends Plantingstructurecheck
{
	public $farmer_id;
	public $huinong;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'plant_id', 'goodseed_id', 'lease_id', 'farms_id','farmer_id','management_area','issame','huinong','state','isbank','bankstate','planter'], 'integer'],
			[['area','contractarea'], 'number'],
			[['zongdi','year','verifydate','lesseepinyin','lessee'], 'string'],
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
        $query = plantingstructurecheck::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
      
//        
        
        $this->load($params);

        $query->andFilterWhere([
            'id' => $this->id,
            'plant_id' => $this->plant_id,
            'area' => $this->area,
            'goodseed_id' => $this->goodseed_id,
        	'lease_id' => $this->lease_id,
            'farms_id' => $this->farms_id,
        	'management_area'=>$this->management_area,
			'year' => $this->year,
        ]);

        $query->andFilterWhere(['like', 'zongdi', $this->zongdi])
        ->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]]);
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
	public function contractareaSearch($str = NULL)
	{
		$this->contractarea = $str;
		if(!empty($this->contractarea)) {
			preg_match_all('/(.*)([0-9]+?)/iU', $this->contractarea, $where);
			//print_r($where);

			// 		string(2) ">="
			// 		string(3) "300"
			if($where[1][0] == '>' or $where[1][0] == '>=')
				$tj = ['between', 'contractarea', (float)$where[2][0],(float)99999.0];
			if($where[1][0] == '<' or $where[1][0] == '<=')
				$tj = ['between', 'contractarea', (float)0.0,(float)$where[2][0]];
			if($where[1][0] == '')
				$tj = ['like', 'contractarea', $this->contractarea];
		} else
			$tj = ['like', 'contractarea', $this->contractarea];
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
	public function lesseepinyinSearch($str = NULL)
	{

		$this->lessee = $str;
		if (preg_match("/^[A-Za-z]/", $this->lessee)) {
			$tj = ['like', 'lesseepinyin', $this->lessee];
		} else {
			$tj = ['like', 'lessee', $this->lessee];
		}
//     	var_dump($tj);exit;
		return $tj;
	}
    public function searchIndex($params)
    {
//     	echo date('Y-m-d',$params['begindate']);
//     	 var_dump($params);exit;
		$query = Plantingstructurecheck::find();

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);
		if(isset($params['plantingstructurecheckSearch']['management_area'])) {
			$this->management_area = $params['plantingstructurecheckSearch']['management_area'];
			if(count($this->management_area) > 1) {
				$this->management_area = NULL;
			}
		}
		$farmid = [];
		if((isset($params['plantingstructurecheckSearch']['farms_id']) and $params['plantingstructurecheckSearch']['farms_id'] !== '') or (isset($params['plantingstructurecheckSearch']['farmer_id']) and $params['plantingstructurecheckSearch']['farmer_id'] !== '')) {
			$farm = Farms::find();
			$farm->andFilterWhere(['management_area'=>$this->management_area]);
		}
		if(isset($params['plantingstructurecheckSearch']['farms_id']) and $params['plantingstructurecheckSearch']['farms_id'] !== '') {
			$this->farms_id = $params['plantingstructurecheckSearch']['farms_id'];
			$farm->andFilterWhere($this->pinyinSearch($this->farms_id));

		}
		if(isset($params['plantingstructurecheckSearch']['farmer_id']) and $params['plantingstructurecheckSearch']['farmer_id'] !== '') {
			$this->farmer_id = $params['plantingstructurecheckSearch']['farmer_id'];
			$farm->andFilterWhere($this->farmerpinyinSearch($this->farmer_id));
		}

		if(isset($params['plantingstructurecheckSearch']['lease_id']) and $params['plantingstructurecheckSearch']['lease_id'] !== '') {
			$this->lease_id = $params['plantingstructurecheckSearch']['lease_id'];
			$query->andFilterWhere($this->lesseepinyinSearch($this->lease_id));
		}

		if(isset($farm)) {
			foreach ($farm->all() as $value) {
				$farmid[] = $value['id'];
			}
		}
//     	var_dump($farmid);exit;
		if(isset($params['plantingstructurecheckSearch']['plant_id']))
			$this->plant_id = $params['plantingstructurecheckSearch']['plant_id'];
		if(is_array($this->plant_id)) {
			$this->plant_id = null;
		}

//		var_dump($this->plant_id);exit;
		if(isset($params['plantingstructurecheckSearch']['state']))
			$this->state = $params['plantingstructurecheckSearch']['state'];
		if(isset($params['plantingstructurecheckSearch']['isbank']))
			$this->isbank = $params['plantingstructurecheckSearch']['isbank'];

//		if(isset($params['plantingstructurecheckSearch']['huinong'])) {
//			$this->huinong = $params['plantingstructurecheckSearch']['huinong'];
//			if($this->huinong == 'farmer') {
//				$query->orFilterWhere(['ddcj_farmer'=>'100%'])->orFilterWhere(['ymcj_farmer'=>'100%']);
//			}
//			if($this->huinong == 'lessee') {
//				$query->orFilterWhere(['ddcj_lessee'=>'100%'])->orFilterWhere(['ymcj_lessee'=>'100%']);
//			}
//			if($this->huinong == 'f_l') {
//				$query->(['ddcj_farmer'=>'100%'])->orFilterWhere(['ymcj_farmer'=>'100%']);
//			}
//		}


		if(isset($params['plantingstructurecheckSearch']['year']))
			$this->year = $params['plantingstructurecheckSearch']['year'];

		if(isset($params['plantingstructurecheckSearch']['issame']))
			$this->issame = $params['plantingstructurecheckSearch']['issame'];

		if(isset($params['plantingstructurecheckSearch']['goodseed_id'])) {
			$this->goodseed_id = $params['plantingstructurecheckSearch']['goodseed_id'];
			$goodseeds = ArrayHelper::map(Goodseed::find()->where(['plant_id'=>$this->plant_id])->all(), 'id', 'id');
			if(!in_array($this->goodseed_id, $goodseeds)) {
				$this->goodseed_id = null;
			}
		}
		if(isset($params['plantingstructurecheckSearch']['planter']))
			$this->planter = $params['plantingstructurecheckSearch']['planter'];
		if(isset($params['plantingstructurecheckSearch']['area']))
			$query->andFilterWhere($this->areaSearch($params['plantingstructurecheckSearch']['area']));

		if(isset($params['plantingstructurecheckSearch']['contractarea']))
			$query->andFilterWhere($this->areaSearch($params['plantingstructurecheckSearch']['contractarea']));
		//         $this->setAttributes($params);
//		var_dump($this->issame);
		$query->andFilterWhere([
//     			'id' => $this->id,
			'plant_id' => $this->plant_id,
			'goodseed_id' => $this->goodseed_id,
			'lease_id' => $this->lease_id,
			'farms_id' => $farmid,
			'management_area' => $this->management_area,
			'year' => $this->year,
			'issame' => $this->issame,
			'state' => $this->state,
			'isbank' => $this->isbank,
			'planter' => $this->planter,
		]);
		if(isset($params['begindate'])) {
			$query->andFilterWhere(['between','update_at',$params['begindate'],$params['enddate']]);
		}
//		var_dump($dataProvider->query->where);exit;
		return $dataProvider;
    }

	public function searchHuinong($params)
	{
//     	echo date('Y-m-d',$params['begindate']);
//     	 var_dump($params);exit;

		$query = Plantingstructurecheck::find()->where(['plant_id'=> Huinong::getPlant('id')]);
//		var_dump($query->where);exit;
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);
		if(isset($params['plantingstructurecheckSearch']['management_area'])) {
			$this->management_area = $params['plantingstructurecheckSearch']['management_area'];
		}
		if(count($this->management_area) > 1) {
//			$management_area = Farms::getManagementArea()['id'];
//			if(count($management_area) > 1)
				$this->management_area = NULL;
//			else
//				$this->management_area = $management_area;
		}
		$farmid = [];
		if((isset($params['plantingstructurecheckSearch']['farms_id']) and $params['plantingstructurecheckSearch']['farms_id'] !== '') or (isset($params['plantingstructurecheckSearch']['farmer_id']) and $params['plantingstructurecheckSearch']['farmer_id'] !== '')) {
			$farm = Farms::find();
			$farm->andFilterWhere(['management_area'=>$this->management_area]);
		}
		if(isset($params['plantingstructurecheckSearch']['farms_id']) and $params['plantingstructurecheckSearch']['farms_id'] !== '') {
			$this->farms_id = $params['plantingstructurecheckSearch']['farms_id'];
			$farm->andFilterWhere($this->pinyinSearch($this->farms_id));

		}
		if(isset($params['plantingstructurecheckSearch']['farmer_id']) and $params['plantingstructurecheckSearch']['farmer_id'] !== '') {
			$this->farmer_id = $params['plantingstructurecheckSearch']['farmer_id'];
			$farm->andFilterWhere($this->farmerpinyinSearch($this->farmer_id));
		}
		if(isset($farm)) {
			foreach ($farm->all() as $value) {
				$farmid[] = $value['id'];
			}
		}
//     	var_dump($farmid);exit;
		if(isset($params['plantingstructurecheckSearch']['plant_id'])) {
			$this->plant_id = $params['plantingstructurecheckSearch']['plant_id'];

		}

		if(isset($params['plantingstructurecheckSearch']['lessee']) and $params['plantingstructurecheckSearch']['lessee'] !== '') {
			$this->lessee = $params['plantingstructurecheckSearch']['lessee'];
			$query->andFilterWhere($this->lesseepinyinSearch($this->lessee));
		}
//		if(isset($params['plantingstructurecheckSearch']['plant_id']))
//			$this->plant_id = $params['plantingstructurecheckSearch']['plant_id'];
//		var_dump($this->plant_id);
//		if(is_array($this->plant_id)) {
//			$this->plant_id = null;
//		}

//		var_dump($this->plant_id);exit;
		if(isset($params['plantingstructurecheckSearch']['state']))
			$this->state = $params['plantingstructurecheckSearch']['state'];
		if(isset($params['plantingstructurecheckSearch']['planter']))
			$this->planter = $params['plantingstructurecheckSearch']['planter'];
		if(isset($params['plantingstructurecheckSearch']['bankstate']))
			$this->bankstate = $params['plantingstructurecheckSearch']['bankstate'];

//		if(isset($params['plantingstructurecheckSearch']['huinong'])) {
//			$this->huinong = $params['plantingstructurecheckSearch']['huinong'];
//			if($this->huinong == 'farmer') {
//				$query->orFilterWhere(['ddcj_farmer'=>'100%'])->orFilterWhere(['ymcj_farmer'=>'100%']);
//			}
//			if($this->huinong == 'lessee') {
//				$query->orFilterWhere(['ddcj_lessee'=>'100%'])->orFilterWhere(['ymcj_lessee'=>'100%']);
//			}
//			if($this->huinong == 'f_l') {
//				$query->(['ddcj_farmer'=>'100%'])->orFilterWhere(['ymcj_farmer'=>'100%']);
//			}
//		}


		if(isset($params['plantingstructurecheckSearch']['year']))
			$this->year = $params['plantingstructurecheckSearch']['year'];

		if(isset($params['plantingstructurecheckSearch']['issame']))
			$this->issame = $params['plantingstructurecheckSearch']['issame'];

		if(isset($params['plantingstructurecheckSearch']['goodseed_id'])) {
			$this->goodseed_id = $params['plantingstructurecheckSearch']['goodseed_id'];
			$goodseeds = ArrayHelper::map(Goodseed::find()->where(['plant_id'=>$this->plant_id])->all(), 'id', 'id');
			if(!in_array($this->goodseed_id, $goodseeds)) {
				$this->goodseed_id = null;
			}
		}
		if(isset($params['plantingstructurecheckSearch']['area']))
			$query->andFilterWhere($this->areaSearch($params['plantingstructurecheckSearch']['area']));

		if(isset($params['plantingstructurecheckSearch']['contractarea']))
			$query->andFilterWhere($this->contractareaSearch($params['plantingstructurecheckSearch']['contractarea']));
		//         $this->setAttributes($params);
//		var_dump($this->issame);
		$query->andFilterWhere([
//     			'id' => $this->id,
			'plant_id' => $this->plant_id,
			'goodseed_id' => $this->goodseed_id,
			'lease_id' => $this->lease_id,
			'farms_id' => $farmid,
			'management_area' => $this->management_area,
			'year' => $this->year,
			'issame' => $this->issame,
			'state' => $this->state,
			'isbank' => $this->isbank,
			'bankstate' => $this->bankstate,
			'planter' => $this->planter
		]);
		if(isset($params['begindate'])) {
			$query->andFilterWhere(['between','update_at',$params['begindate'],$params['enddate']]);
		}
//		var_dump($dataProvider->query->where);exit;
		return $dataProvider;
	}

	public function searchHuinongsearch($params)
	{
//     	echo date('Y-m-d',$params['begindate']);
//     	 print_r($params);exit;

		$query = Plantingstructurecheck::find();
//		var_dump($query->where);exit;
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);
		if(isset($params['plantingstructurecheckSearch']['management_area'])) {
			$this->management_area = $params['plantingstructurecheckSearch']['management_area'];
		}
		if(count($this->management_area) > 1) {
//			$management_area = Farms::getManagementArea()['id'];
//			if(count($management_area) > 1)
			$this->management_area = NULL;
//			else
//				$this->management_area = $management_area;
		}
		$farmid = [];
		if((isset($params['plantingstructurecheckSearch']['farms_id']) and $params['plantingstructurecheckSearch']['farms_id'] !== '') or (isset($params['plantingstructurecheckSearch']['farmer_id']) and $params['plantingstructurecheckSearch']['farmer_id'] !== '')) {
			$farm = Farms::find();
			$farm->andFilterWhere(['management_area'=>$this->management_area]);
		}
		if(isset($params['plantingstructurecheckSearch']['farms_id']) and $params['plantingstructurecheckSearch']['farms_id'] !== '') {
			$this->farms_id = $params['plantingstructurecheckSearch']['farms_id'];
			$farm->andFilterWhere($this->pinyinSearch($this->farms_id));

		}
		if(isset($params['plantingstructurecheckSearch']['farmer_id']) and $params['plantingstructurecheckSearch']['farmer_id'] !== '') {
			$this->farmer_id = $params['plantingstructurecheckSearch']['farmer_id'];
			$farm->andFilterWhere($this->farmerpinyinSearch($this->farmer_id));
		}
		if(isset($farm)) {
			foreach ($farm->all() as $value) {
				$farmid[] = $value['id'];
			}
		}
//     	var_dump($farmid);exit;
		if(isset($params['plantingstructurecheckSearch']['plant_id'])) {
			$this->plant_id = $params['plantingstructurecheckSearch']['plant_id'];

		}
//		var_dump($this->plant_id);exit;
		if(isset($params['plantingstructurecheckSearch']['lessee']) and $params['plantingstructurecheckSearch']['lessee'] !== '') {
			$this->lessee = $params['plantingstructurecheckSearch']['lessee'];
			$query->andFilterWhere($this->lesseepinyinSearch($this->lessee));
		}
//		if(isset($params['plantingstructurecheckSearch']['plant_id']))
//			$this->plant_id = $params['plantingstructurecheckSearch']['plant_id'];
//		var_dump($this->plant_id);
//		if(is_array($this->plant_id)) {
//			$this->plant_id = null;
//		}

//		var_dump($this->plant_id);exit;
		if(isset($params['plantingstructurecheckSearch']['state']))
			$this->state = $params['plantingstructurecheckSearch']['state'];
		if(isset($params['plantingstructurecheckSearch']['planter']))
			$this->planter = $params['plantingstructurecheckSearch']['planter'];
		if(isset($params['plantingstructurecheckSearch']['bankstate']))
			$this->bankstate = $params['plantingstructurecheckSearch']['bankstate'];

//		if(isset($params['plantingstructurecheckSearch']['huinong'])) {
//			$this->huinong = $params['plantingstructurecheckSearch']['huinong'];
//			if($this->huinong == 'farmer') {
//				$query->orFilterWhere(['ddcj_farmer'=>'100%'])->orFilterWhere(['ymcj_farmer'=>'100%']);
//			}
//			if($this->huinong == 'lessee') {
//				$query->orFilterWhere(['ddcj_lessee'=>'100%'])->orFilterWhere(['ymcj_lessee'=>'100%']);
//			}
//			if($this->huinong == 'f_l') {
//				$query->(['ddcj_farmer'=>'100%'])->orFilterWhere(['ymcj_farmer'=>'100%']);
//			}
//		}


		if(isset($params['plantingstructurecheckSearch']['year']))
			$this->year = $params['plantingstructurecheckSearch']['year'];

		if(isset($params['plantingstructurecheckSearch']['issame']))
			$this->issame = $params['plantingstructurecheckSearch']['issame'];

		if(isset($params['plantingstructurecheckSearch']['goodseed_id'])) {
			$this->goodseed_id = $params['plantingstructurecheckSearch']['goodseed_id'];
			$goodseeds = ArrayHelper::map(Goodseed::find()->where(['plant_id'=>$this->plant_id])->all(), 'id', 'id');
			if(!in_array($this->goodseed_id, $goodseeds)) {
				$this->goodseed_id = null;
			}
		}
		if(isset($params['plantingstructurecheckSearch']['area']))
			$query->andFilterWhere($this->areaSearch($params['plantingstructurecheckSearch']['area']));

		if(isset($params['plantingstructurecheckSearch']['contractarea']))
			$query->andFilterWhere($this->contractareaSearch($params['plantingstructurecheckSearch']['contractarea']));
		//         $this->setAttributes($params);
//		var_dump($this->issame);
//		if(isset($params['yearplant'])) {
//			foreach($params['yearplant'] as $year => $plant) {
//				$query->andFilterWhere(['year'=>$year,'plant_id'=>$plant]);
//			}
//		}
//		if(count($this->plant_id) > 1) {
//			$this->plant_id = null;
//		}

		$query->andFilterWhere([
//     			'id' => $this->id,
			'plant_id' => $this->plant_id,
			'goodseed_id' => $this->goodseed_id,
			'lease_id' => $this->lease_id,
			'farms_id' => $farmid,
			'management_area' => $this->management_area,
			'year' => $this->year,
			'issame' => $this->issame,
			'state' => $this->state,
			'isbank' => $this->isbank,
			'bankstate' => $this->bankstate,
			'planter' => $this->planter
		]);
//		if(isset($params['begindate'])) {
			$query->andFilterWhere(['between','update_at',$params['begindate'],$params['enddate']]);
//		}
//		print_r($dataProvider->query->where);exit;
		return $dataProvider;
	}
}
