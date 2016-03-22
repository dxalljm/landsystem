<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Collection;
use app\models\Theyear;
use app\models\Farms;
/**
 * collectionSearch represents the model behind the search form about `app\models\Collection`.
 */
class collectionSearch extends Collection
{
	public $farmer_id;
    /**
     * @inheritdoc
     */
	public $farmname;
	public function rules()
    {
        return [
            [['id', 'payyear','farms_id', 'farmer_id','ypayyear', 'isupdate','dckpay','create_at','update_at','management_area'], 'integer'],
            [['farmname', 'billingtime','nonumber','year'], 'safe'],
            [['ypayarea', 'amounts_receivable', 'real_income_amount', 'ypaymoney', 'owe'], 'number'],
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
//     	var_dump($params);
//     	exit;
        $query = Collection::find();
        $query->joinWith(['farms']);
		
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
       
        $dataProvider->setSort([
        		'attributes' => [
        
        				'farmname' => [
        						'asc' => ['farms.farmname' => SORT_ASC],
        						'desc' => ['farms.farmname' => SORT_DESC],
        						//'label' => '管理区',
        				],
        
        		]
        ]);
        
		if(empty($this->ypayyear))
			$this->ypayyear = Theyear::findOne(1)['years'];
// 		var_dump($this->ypayyear);exit;
        $this->load($params);        
        
       $query->andFilterWhere([
            'id' => $this->id,
            'farms_id' => $this->farms_id,
            'payyear' => $this->payyear,
       		'ypayyear' => $this->ypayyear,
       		'year' => $this->year,
//             'ypayarea' => $this->ypayarea,
//             'ypaymoney' => $this->ypaymoney,
            'owe' => $this->owe,
       		'dckpay' => $this->dckpay,
            'isupdate' => $this->isupdate,
       		'land_collection.management_area' => $this->management_area,
        ]);

        $query->andFilterWhere(['like', 'billingtime', $this->billingtime])
            ->andFilterWhere($this->betweenSearch('amounts_receivable'))
    		->andFilterWhere($this->betweenSearch('real_income_amount'))
    		->andFilterWhere(['like', 'nonumber', $this->nonumber])
    		->andFilterWhere($this->betweenSearch('ypayarea'))
    		->andFilterWhere($this->betweenSearch('ypaymoney'))
            ->andFilterWhere(['like', 'land_farms.farmname', $this->farmname]);
//             ->andFilterWhere(['between','land_collection.update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]]);

        return $dataProvider;
    }
    public function searchIndex($params)
    {
    	$query = Collection::find();
//     	$query->joinWith(['farms']);
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    	]);
    
    	if($params['collectionSearch']['management_area'] == 0)
    		$this->management_area = NULL;
    	else
    		$this->management_area = $params['collectionSearch']['management_area'];
    	$farmid = [];
    	if((isset($params['collectionSearch']['farms_id']) and $params['collectionSearch']['farms_id'] !== '') or (isset($params['collectionSearch']['farmer_id']) and $params['collectionSearch']['farmer_id'] !== '')) {
	    	$farm = Farms::find();
	    	$farm->andFilterWhere(['management_area'=>$this->management_area]);
    	}
    	if(isset($params['collectionSearch']['farms_id']) and $params['collectionSearch']['farms_id'] !== '') {
    		$this->farms_id = $params['collectionSearch']['farms_id'];
    		$farm->andFilterWhere($this->pinyinSearch($this->farms_id));
    		    		
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
    	if(isset($params['loanSearch']['amounts_receivable']) and $params['loanSearch']['amounts_receivable'] !== '') {
    		$query->andFilterWhere($this->numberSearch('amounts_receivable',$params['loanSearch']['amounts_receivable']));
    	}
    	if(isset($params['loanSearch']['real_income_amount']) and $params['loanSearch']['real_income_amount'] !== '') {
    		$query->andFilterWhere($this->numberSearch('real_income_amount',$params['loanSearch']['real_income_amount']));
    	}
    	if(isset($params['loanSearch']['owe']) and $params['loanSearch']['owe'] !== '') {
    		$query->andFilterWhere($this->numberSearch('owe',$params['loanSearch']['owe']));
    	}
    	if(isset($params['loanSearch']['ypayarea']) and $params['loanSearch']['ypayarea'] !== '') {
    		$query->andFilterWhere($this->numberSearch('ypayarea',$params['loanSearch']['ypayarea']));
    	}
    	if(isset($params['loanSearch']['ypaymoney']) and $params['loanSearch']['ypaymoney'] !== '') {
    		$query->andFilterWhere($this->numberSearch('ypaymoney',$params['loanSearch']['ypaymoney']));
    	}
    	$query->andFilterWhere([
    			'id' => $this->id,
    			'farms_id' => $farmid,
    			'payyear' => $this->payyear,
    			'ypayyear' => $this->ypayyear,
    			'ypayarea' => $this->ypayarea,
    			'ypaymoney' => $this->ypaymoney,
    			'owe' => $this->owe,
    			'dckpay' => $this->dckpay,
    			'isupdate' => $this->isupdate,
    			'management_area' => $this->management_area,
    	]);
    
    	$query->andFilterWhere(['between','update_at',$params['begindate'],$params['enddate']]);
    
    	return $dataProvider;
    }
}
