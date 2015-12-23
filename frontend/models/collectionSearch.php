<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Collection;
use app\models\Theyear;
/**
 * collectionSearch represents the model behind the search form about `app\models\Collection`.
 */
class collectionSearch extends Collection
{
	
    /**
     * @inheritdoc
     */
	public $farmname;
	public function rules()
    {
        return [
            [['id', 'payyear','farms_id', 'ypayyear', 'isupdate','dckpay','create_at','update_at','management_area'], 'integer'],
            [['farmname', 'billingtime'], 'safe'],
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
			$this->ypayyear = date('Y');
// 		var_dump($this->ypayyear);exit;
        $this->load($params);        
        
       $query->andFilterWhere([
            'id' => $this->id,
            'farms_id' => $this->farms_id,
            'payyear' => $this->payyear,
       		'ypayyear' => $this->ypayyear,
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
    		->andFilterWhere($this->betweenSearch('ypayarea'))
    		->andFilterWhere($this->betweenSearch('ypaymoney'))
            ->andFilterWhere(['like', 'land_farms.farmname', $this->farmname])
            ->andFilterWhere(['between','land_collection.update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]]);

        return $dataProvider;
    }
    public function searchIndex($params)
    {
    	$query = Collection::find();
//     	$query->joinWith(['farms']);
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
    
    	if(isset($params['farms_id']))
    		$farms_id = $params['farms_id'];
    	else
    		$farms_id = $this->farms_id;
    
    	if (!$this->validate()) {
    		// uncomment the following line if you do not want to any records when validation fails
    		// $query->where('0=1');
    		return $dataProvider;
    	}
    
    
    
    	$query->andFilterWhere([
    			'id' => $this->id,
    			'farms_id' => $farms_id,
    			'payyear' => $this->payyear,
    			'ypayyear' => $this->ypayyear,
    			'ypayarea' => $this->ypayarea,
    			'ypaymoney' => $this->ypaymoney,
    			'owe' => $this->owe,
    			'dckpay' => $this->dckpay,
    			'isupdate' => $this->isupdate,
    	]);
    
    	$query->andFilterWhere(['like', 'billingtime', $this->billingtime])
    	->andFilterWhere(['like', 'amounts_receivable', $this->amounts_receivable])
    	->andFilterWhere(['like', 'real_income_amount', $this->real_income_amount])
    	->andFilterWhere(['like', 'land_farms.farmname', $this->farmname])
    	->andFilterWhere(['between','land.collection.update_at',$params['begindate'],$params['enddate']]);
    
    	return $dataProvider;
    }
}
