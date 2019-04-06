<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Collection;
use app\models\Theyear;
use app\models\Farms;
use app\models\User;
/**
 * collectionSearch represents the model behind the search form about `app\models\Collection`.
 */
class collectionSearch extends Collection
{
	public $farmer_id;
	public $contractnumber;
    /**
     * @inheritdoc
     */
	public $farmname;
	public function rules()
    {
        return [
            [['id', 'payyear','farms_id', 'farmer_id','ypayyear', 'isupdate','dckpay','create_at','update_at','management_area','state','iscq','farmstate'], 'integer'],
            [['farmname', 'billingtime','nonumber','year','contractarea'], 'safe'],
            [['ypayarea', 'amounts_receivable', 'real_income_amount', 'ypaymoney', 'owe','measure','contractnumber'], 'number'],
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

    public function betweenSearch($str)
    {
    	if(!empty($this->$str)) {
    		preg_match_all('/(.*)([0-9]+?)/iU', $this->$str, $where);
    		//print_r($where);
    
    		// 		string(2) ">="
    		// 		string(3) "300"
    		if($where[1][0] == '>' or $where[1][0] == '>=')
    			$tj = ['between', $str, (float)$where[2][0],(float)99999.0];
    		if($where[1][0] == '<' or $where[1][0] == '<=')
    			$tj = ['between', $str, (float)0.0,(float)$where[2][0]];
    		if($where[1][0] == '')
    			$tj = ['like', $str, $this->$str];
    	} else
    		$tj = ['like', $str, $this->$str];
    	//var_dump($tj);
    	return $tj;
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
	public function searchIndex($params)
	{
// 		var_dump(date('Y-m-d H:i:s',$params['begindate']));var_dump($params['begindate']);
//     	var_dump(date('Y-m-d H:i:s',$params['enddate']));var_dump($params['enddate']);
// 		var_dump($params);exit;
		$query = Collection::find();
//     	$query->joinWith(['farms']);
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);
// 		var_dump($params);exit;
		if(isset($params['collectionSearch']['management_area'])) {
			if($params['collectionSearch']['management_area'] == 0)
				$this->management_area = NULL;
			else
				$this->management_area = $params['collectionSearch']['management_area'];
		} else {
			$management_area = Farms::getManagementArea()['id'];
			if(count($management_area) > 1)
				$this->management_area = NULL;
			else 
				$this->management_area = $management_area;
		}

//		if(isset($params['collectionSearch']['managementa_area']) and $params['collectionSearch']['managementa_area'] !== '') {
//			$this->managementa_area = $params['collectionSearch']['managementa_area'];
//
//		}
		$farm = Farms::find();
//		if((isset($params['collectionSearch']['farms_id']) and $params['collectionSearch']['farms_id'] !== '') or (isset($params['collectionSearch']['farmer_id']) and $params['collectionSearch']['farmer_id'] !== '') or (isset($params['collectionSearch']['contractnumber']) and $params['collectionSearch']['contractnumber'] !== '')) {
//			$farm->andFilterWhere(['management_area'=>$this->management_area]);
//		}
		if(isset($params['collectionSearch']['farms_id']) and $params['collectionSearch']['farms_id'] !== '') {
			$this->farms_id = $params['collectionSearch']['farms_id'];
			$farm->andFilterWhere($this->pinyinSearch($this->farms_id));
		}

		if(isset($params['collectionSearch']['contractnumber']) and $params['collectionSearch']['contractnumber'] !== '') {
			$this->farmstate = $params['collectionSearch']['contractnumber'];
			$this->contractnumber = $params['collectionSearch']['contractnumber'];
		}
		if(isset($params['collectionSearch']['contractarea']) and $params['collectionSearch']['contractarea'] !== '') {
			$this->contractarea = $params['collectionSearch']['contractarea'];
//			$farm->andFilterWhere(['like','contractarea',$this->contractarea]);
		}
// 		if(isset($params['collectionSearch']['payyear']) and $params['collectionSearch']['payyear'] !== '') {
// 			$this->payyear = $params['collectionSearch']['payyear'];
// 		}

		if(isset($params['collectionSearch']['farmer_id']) and $params['collectionSearch']['farmer_id'] !== '') {
			$this->farmer_id = $params['collectionSearch']['farmer_id'];
			$farm->andFilterWhere($this->farmerpinyinSearch($this->farmer_id));
		}
		if(isset($params['collectionSearch']['payyear']) and $params['collectionSearch']['payyear'] !== '') {
			$this->payyear = $params['collectionSearch']['payyear'];
			$query->andFilterWhere(['payyear' => $this->payyear]);
		}
		if(!empty($farm->where)) {
// 			var_dump($farm->all());exit;
			$farmid = [];
			foreach ($farm->all() as $value) {
				$farmid[] = $value['id'];
			}
			if(empty($farmid))
				$farmid = -1;
		}
// 		var_dump($farmid);exit;
		if(isset($farmid) and !empty($farmid)) {
			$query->andFilterWhere(['farms_id' => $farmid]);
		}
//		var_dump($params['collectionSearch']['state']);
		if (isset($params['collectionSearch']['state']) and $params['collectionSearch']['state'] !== '') {
//			var_dump($params['collectionSearch']['state']);exit;
			switch ($params['collectionSearch']['state']) {
				case 4:
					$this->iscq = 1;
					$this->state = $params['collectionSearch']['state'];
					$query->andFilterWhere(['state'=>1]);
					break;
				case 3:
					$this->dckpay = 1;
					$this->state = $params['collectionSearch']['state'];
					$query->andFilterWhere(['state'=>0]);
					break;
				case 1:
					$this->state = $params['collectionSearch']['state'];
					$query->andFilterWhere(['between','state',1,2]);
					break;
				default:
					$this->state = $params['collectionSearch']['state'];
					$query->andFilterWhere(['state'=>$this->state]);
			}
		}
		if(isset($params['collectionSearch']['amounts_receivable']) and $params['collectionSearch']['amounts_receivable'] !== '') {
			$query->andFilterWhere($this->numberSearch('amounts_receivable',$params['collectionSearch']['amounts_receivable']));
		}
		if(isset($params['collectionSearch']['measure']) and $params['collectionSearch']['measure'] !== '') {
			$query->andFilterWhere($this->numberSearch('measure',$params['collectionSearch']['measure']));
		}
		if(isset($params['collectionSearch']['real_income_amount']) and $params['collectionSearch']['real_income_amount'] !== '') {
			$query->andFilterWhere($this->numberSearch('real_income_amount',$params['collectionSearch']['real_income_amount']));
		}
		if(isset($params['collectionSearch']['owe']) and $params['collectionSearch']['owe'] !== '') {
			$query->andFilterWhere($this->numberSearch('owe',$params['collectionSearch']['owe']));
		}
		if(isset($params['collectionSearch']['ypayarea']) and $params['collectionSearch']['ypayarea'] !== '') {
			$query->andFilterWhere($this->numberSearch('ypayarea',$params['collectionSearch']['ypayarea']));
		}
		if(isset($params['collectionSearch']['ypaymoney']) and $params['collectionSearch']['ypaymoney'] !== '') {
			$query->andFilterWhere($this->numberSearch('ypaymoney',$params['collectionSearch']['ypaymoney']));
		}
		if(isset($params['begindate']) and isset($params['enddate'])) {
			$query->andFilterWhere(['between','update_at',$params['begindate'],$params['enddate']]);
		}
		$query->andFilterWhere([
			'id' => $this->id,
// 			'farms_id' => $farmid,
//    			'payyear' => $this->payyear,
   			'measure' => $this->measure,
			'ypayarea' => $this->ypayarea,
			'ypaymoney' => $this->ypaymoney,
			'owe' => $this->owe,
			'dckpay' => $this->dckpay,
			'isupdate' => $this->isupdate,
			'management_area' => $this->management_area,
			'iscq' => $this->iscq,
			'farmstate' => $this->farmstate,
// 			'state' => $this->state,
		]);

//		$query->andFilterWhere(['payyear'=>User::getYear()]);
// 		if(isset($params['begindate']) and isset($params['enddate']))
// 			$query->andFilterWhere(['between','update_at',$params['begindate'],$params['enddate']]);
// 		var_dump($dataProvider->query->where);exit;
		return $dataProvider;
	}

	public function noSearchIndex($params)
	{
		$query = Collection::find()->Where(['farms_id'=>0]);
//     	$query->joinWith(['farms']);
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);
// 		var_dump($params);exit;
		if(isset($params['collectionSearch']['management_area'])) {
			if($params['collectionSearch']['management_area'] == 0)
				$this->management_area = NULL;
			else
				$this->management_area = $params['collectionSearch']['management_area'];
		} else {
			$this->management_area = NULL;
		}

//		if(isset($params['collectionSearch']['managementa_area']) and $params['collectionSearch']['managementa_area'] !== '') {
//			$this->managementa_area = $params['collectionSearch']['managementa_area'];
//
//		}
		$farmid = [];
		if((isset($params['collectionSearch']['farms_id']) and $params['collectionSearch']['farms_id'] !== '') or (isset($params['collectionSearch']['farmer_id']) and $params['collectionSearch']['farmer_id'] !== '')) {
			$farm = Farms::find();
			$farm->andFilterWhere(['management_area'=>$this->management_area]);
		}
		if(isset($params['collectionSearch']['farms_id']) and $params['collectionSearch']['farms_id'] !== '') {
			$this->farms_id = $params['collectionSearch']['farms_id'];
			$farm->andFilterWhere($this->pinyinSearch($this->farms_id));
		}

		if(isset($params['collectionSearch']['payyear']) and $params['collectionSearch']['payyear'] !== '') {
			$this->payyear = $params['collectionSearch']['payyear'];
		}
		if(isset($params['collectionSearch']['state']) and $params['collectionSearch']['state'] !== '') {
			$this->state = $params['collectionSearch']['state'];
		}
		if(isset($params['collectionSearch']['farmer_id']) and $params['collectionSearch']['farmer_id'] !== '') {
			$this->farmer_id = $params['collectionSearch']['farmer_id'];
			$farm->andFilterWhere($this->farmerpinyinSearch($this->farmer_id));
		}
		if(isset($farm)) {
			foreach ($farm->all() as $value) {
				$farmid[] = $value['id'];
			}
		}
		if(isset($params['collectionSearch']['amounts_receivable']) and $params['collectionSearch']['amounts_receivable'] !== '') {
			$query->andFilterWhere($this->numberSearch('amounts_receivable',$params['collectionSearch']['amounts_receivable']));
		}
		if(isset($params['collectionSearch']['real_income_amount']) and $params['collectionSearch']['real_income_amount'] !== '') {
			$query->andFilterWhere($this->numberSearch('real_income_amount',$params['collectionSearch']['real_income_amount']));
		}
		if(isset($params['collectionSearch']['owe']) and $params['collectionSearch']['owe'] !== '') {
			$query->andFilterWhere($this->numberSearch('owe',$params['collectionSearch']['owe']));
		}
		if(isset($params['collectionSearch']['ypayarea']) and $params['collectionSearch']['ypayarea'] !== '') {
			$query->andFilterWhere($this->numberSearch('ypayarea',$params['collectionSearch']['ypayarea']));
		}
		if(isset($params['collectionSearch']['ypaymoney']) and $params['collectionSearch']['ypaymoney'] !== '') {
			$query->andFilterWhere($this->numberSearch('ypaymoney',$params['collectionSearch']['ypaymoney']));
		}
		$query->andFilterWhere([
			'id' => $this->id,
// 			'farms_id' => $farmid,
//    			'payyear' => $this->payyear,
//    			'ypayyear' => $this->ypayyear,
			'ypayarea' => $this->ypayarea,
			'ypaymoney' => $this->ypaymoney,
			'owe' => $this->owe,
			'dckpay' => $this->dckpay,
			'isupdate' => $this->isupdate,
			'management_area' => $this->management_area,
			'state' => $this->state,
		]);

		$query->andFilterWhere(['payyear'=>$this->payyear]);
		if(isset($params['begindate']) and isset($params['enddate']))
			$query->andFilterWhere(['between','update_at',$params['begindate'],$params['enddate']]);
// 		var_dump($dataProvider->models);exit;
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
	public function search($params)
    {
     	var_dump($params);
//     	exit
        $query = Collection::find()->where(['dckpay'=>1,'state'=>0]);
//        $query->joinWith(['farms']);
		$collctionFarmsID = [];
		foreach ($query->all() as $item) {
			$collctionFarmsID[] = $item['farms_id'];
		}
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


//        $dataProvider->setSort([
//        		'attributes' => [
//
//        				'farmname' => [
//        						'asc' => ['farms.farmname' => SORT_ASC],
//        						'desc' => ['farms.farmname' => SORT_DESC],
//        						//'label' => '管理区',
//        				],
//
//        		]
//        ]);

//		if(empty($this->payyear))
//			$this->payyear = Theyear::getYear();
// 		var_dump($this->payyear);exit;
        $this->load($params);
//        var_dump($this->state);exit;
//		var_dump($this->management_area);
		if($this->farms_id !== '' or $this->farmer_id !== '') {
			$farm = Farms::find()->andFilterWhere(['id'=>$collctionFarmsID]);
//			$farm->andFilterWhere(['management_area'=>$this->management_area]);
		}
		if($this->farms_id !== '') {
//			$this->farms_id = $params['collectionSearch']['farms_id'];
			$farm->andFilterWhere($this->pinyinSearch($this->farms_id));

		}
		if($this->farmer_id !== '') {
//			$this->farmer_id = $params['collectionSearch']['farmer_id'];
			$farm->andFilterWhere($this->pinyinSearch($this->farmer_id));
//			var_dump($this->pinyinSearch($this->farmer_id));
		}
		if(isset($farm)) {
			$farmid = [];
//			var_dump($farm->where);
			foreach ($farm->all() as $value) {
				$farmid[] = $value['id'];
			}
//			var_dump($farmid);
			$query->andFilterWhere(['farms_id' => $farmid]);
//			var_dump($query->where);
		} else
			$query->andFilterWhere(['farms_id' => $this->farms_id]);

       $query->andFilterWhere([
            'id' => $this->id,

            'payyear' => $this->payyear,
       		'ypayyear' => $this->ypayyear,
       		'year' => $this->year,
             'ypayarea' => $this->ypayarea,
             'ypaymoney' => $this->ypaymoney,
            'owe' => $this->owe,
       		'dckpay' => $this->dckpay,
            'isupdate' => $this->isupdate,
       		'management_area' => $this->management_area,
       		'state' => $this->state,
        ]);
        $query->andFilterWhere(['like', 'billingtime', $this->billingtime])
            ->andFilterWhere($this->betweenSearch('amounts_receivable'))
    		->andFilterWhere($this->betweenSearch('real_income_amount'))
    		->andFilterWhere(['like', 'nonumber', $this->nonumber])
    		->andFilterWhere($this->betweenSearch('ypayarea'))
    		->andFilterWhere($this->betweenSearch('ypaymoney'));
//            ->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]]);
//		var_dump($dataProvider->query->where);exit;
        return $dataProvider;
    }

	public function searchCollection($params)
	{
		$query = Collection::find()->where(['dckpay'=>1,'state'=>0]);
//     	$query->joinWith(['farms']);
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);
// 		var_dump($params);exit;
		if(isset($params['collectionSearch']['management_area'])) {
			if($params['collectionSearch']['management_area'] == 0)
				$this->management_area = NULL;
			else
				$this->management_area = $params['collectionSearch']['management_area'];
		} else {
			$management_area = Farms::getManagementArea()['id'];
			if(count($management_area) > 1)
				$this->management_area = NULL;
			else 
				$this->management_area = $management_area;
		}

//		if(isset($params['collectionSearch']['managementa_area']) and $params['collectionSearch']['managementa_area'] !== '') {
//			$this->managementa_area = $params['collectionSearch']['managementa_area'];
//
//		}
		$farmid = [];
		if((isset($params['collectionSearch']['farms_id']) and $params['collectionSearch']['farms_id'] !== '') or (isset($params['collectionSearch']['farmer_id']) and $params['collectionSearch']['farmer_id'] !== '') or (isset($params['collectionSearch']['contractnumber']) and $params['collectionSearch']['contractnumber'] !== '')) {
			$farm = Farms::find();
			$farm->andFilterWhere(['management_area'=>$this->management_area]);
		}
		if(isset($params['collectionSearch']['farms_id']) and $params['collectionSearch']['farms_id'] !== '') {
			$this->farms_id = $params['collectionSearch']['farms_id'];
			$farm->andFilterWhere($this->pinyinSearch($this->farms_id));
		}

		if(isset($params['collectionSearch']['contractnumber']) and $params['collectionSearch']['contractnumber'] !== '') {
			$this->farmstate = $params['collectionSearch']['contractnumber'];
		}
//
		if(isset($params['collectionSearch']['payyear']) and $params['collectionSearch']['payyear'] !== '') {
			$this->payyear = $params['collectionSearch']['payyear'];
		}

		if(isset($params['collectionSearch']['farmer_id']) and $params['collectionSearch']['farmer_id'] !== '') {
			$this->farmer_id = $params['collectionSearch']['farmer_id'];
			$farm->andFilterWhere($this->farmerpinyinSearch($this->farmer_id));
		}
//		var_dump($farm->where);
		if(isset($farm)) {
			if($farm->all()) {
				foreach ($farm->all() as $value) {
					$farmid[] = $value['id'];
				}
			} else {
				$farmid = false;
			}
		}
		if(isset($params['collectionSearch']['measure']) and $params['collectionSearch']['measure'] !== '') {
			$query->andFilterWhere($this->numberSearch('measure',$params['collectionSearch']['measure']));
		}
		if(isset($params['collectionSearch']['amounts_receivable']) and $params['collectionSearch']['amounts_receivable'] !== '') {
			$query->andFilterWhere($this->numberSearch('amounts_receivable',$params['collectionSearch']['amounts_receivable']));
		}
		if(isset($params['collectionSearch']['real_income_amount']) and $params['collectionSearch']['real_income_amount'] !== '') {
			$query->andFilterWhere($this->numberSearch('real_income_amount',$params['collectionSearch']['real_income_amount']));
		}
		if(isset($params['collectionSearch']['owe']) and $params['collectionSearch']['owe'] !== '') {
			$query->andFilterWhere($this->numberSearch('owe',$params['collectionSearch']['owe']));
		}
		if(isset($params['collectionSearch']['ypayarea']) and $params['collectionSearch']['ypayarea'] !== '') {
			$query->andFilterWhere($this->numberSearch('ypayarea',$params['collectionSearch']['ypayarea']));
		}
		if(isset($params['collectionSearch']['ypaymoney']) and $params['collectionSearch']['ypaymoney'] !== '') {
			$query->andFilterWhere($this->numberSearch('ypaymoney',$params['collectionSearch']['ypaymoney']));
		}
//		var_dump($farmid);
		$query->andFilterWhere([
			'id' => $this->id,
			'farms_id' => $farmid,
			'payyear' => $this->payyear,
			'ypayarea' => $this->ypayarea,
			'ypaymoney' => $this->ypaymoney,
			'owe' => $this->owe,
			'dckpay' => $this->dckpay,
			'isupdate' => $this->isupdate,
			'management_area' => $this->management_area,
			'state' => $this->state,
			'farmstate' => $this->farmstate,
		]);

//		$query->andFilterWhere(['payyear'=>$this->payyear]);
//		if(isset($params['begindate']) and isset($params['enddate']))
//			$query->andFilterWhere(['between','update_at',$params['begindate'],$params['enddate']]);
// 		var_dump($dataProvider->query->where);exit;
		return $dataProvider;
	}
}
