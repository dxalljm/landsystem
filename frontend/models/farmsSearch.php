<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\farms;
use app\models\ManagementArea;

/**
 * farmsSearch represents the model behind the search form about `app\models\farms`.
 */
class farmsSearch extends Farms
{
	public $farmername;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'create_at', 'update_at','state','oldfarms_id','locked'], 'integer'],
            [['farmname', 'farmername', 'address','measure', 'management_area','telephone', 'spyear', 'zongdi', 'cooperative_id','notclear','surveydate', 'groundsign', 'farmersign', 'pinyin','farmerpinyin','contractnumber', 'begindate', 'enddate','latitude','longitude'], 'safe'],
            //[['measure'], 'number'],
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

    
    public function betweenSearch()
    {
    	if(!empty($this->measure)) {
			preg_match_all('/(.*)([0-9]+?)/iU', $this->measure, $where);
			//print_r($where);
	
	// 		string(2) ">="
	// 		string(3) "300"
	    	if($where[1][0] == '>' or $where[1][0] == '>=')
	    		$tj = ['between', 'measure', (float)$where[2][0],(float)99999.0];
	    	if($where[1][0] == '<' or $where[1][0] == '<=')
	    		$tj = ['between', 'measure', (float)0.0,(float)$where[2][0]];
	    	if($where[1][0] == '')
	    		$tj = ['like', 'measure', $this->measure];
    	} else
    		$tj = ['like', 'measure', $this->measure];
    	//var_dump($tj);
    	return $tj;
    }
    
    public function pinyinSearch($str = NULL)
    {
    	if(empty($str))
    		$farmname = $this->farmname;
    	else
    		$farmname = $str;
    	if (preg_match ("/^[A-Za-z]/", $farmname)) {
    		$tj = ['like','pinyin',$farmname];
    	} else {
    		$tj = ['like','farmname',$farmname];
    	}
		return $tj;
    }
    
    public function farmerpinyinSearch($str = NULL)
    {
    	if(empty($str))
    		$farmername = $this->farmername;
    	else
    		$farmername = $str;
    	if (preg_match ("/^[A-Za-z]/", $farmername)) {
    		$tj = ['like','farmerpinyin',$farmername];
    	} else {
    		$tj = ['like','farmername',$farmername];
    	}
//     	var_dump($tj);exit;
    	return $tj;
    }
    
    public function getManagementWhere($managemetnarea)
    {
//     	if($managemetnarea)
    		
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
//        exit;
        $query = farms::find();
        //$query->joinWith(['farmer']);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
//         		'pagination' => [
//         				'pageSize' => 0,
//         		],
        ]);
		//var_dump($params['farmsSearch']['measure']);
//         print_r($params['farmsSearch']);
//         $this->betweenSearch($params['farmsSearch']['measure']);
//        $dataProvider->setSort([
//         		'attributes' => [
        				 
//         				'id' => [
//         						'asc' => ['id' => SORT_ASC],
//         						'desc' => ['id' => SORT_DESC],
//         						//'label' => '管理区',
//         				],
//         				'farmname' => [
//         						'asc' => ['farmname' => SORT_ASC],
//         						'desc' => ['farmname' => SORT_DESC],
//         						//'label' => '管理区',
//         				],
//         				'farmername' => [
//         						'asc' => ['land_farmer.farmername' => SORT_ASC],
//         						'desc' => ['land_farmer.farmername' => SORT_DESC],
//         						'label' => '法人姓名',
//         				],
//         				'measure' => [
//         						'asc' => ['measure' => SORT_ASC],
//         						'desc' => ['measure' => SORT_DESC],
//         						//'label' => '管理区',
//         				],
//         		] 
//         ]);
		
        
     	$this->load($params);
//     	$this->management_area = $params['farmsSearch']['management_area'];
        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        //var_dump($dataProvider);
        $query->andFilterWhere([
            'id' => $this->id,
        	'locked' => $this->locked,
        	'management_area' => $this->management_area,
        ]);
        //$this->management_area = [1, 4, 5];

          $query->andFilterWhere($this->pinyinSearch())
            ->andFilterWhere($this->farmerpinyinSearch())
            ->andFilterWhere(['like', 'cardid', $this->cardid])
            ->andFilterWhere(['like', 'telephone', $this->telephone])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'state', $this->state])
            ->andFilterWhere(['like', 'oldfarms_id', $this->oldfarms_id])
//             ->andWhere(['management_area' => $managementarea])
            ->andFilterWhere(['like', 'spyear', $this->spyear])
            ->andFilterWhere(['like', 'zongdi', $this->zongdi])
            ->andFilterWhere(['like', 'notclear', $this->notclear])
            ->andFilterWhere(['like', 'cooperative_id', $this->cooperative_id])
            ->andFilterWhere(['like', 'surveydate', $this->surveydate])
            ->andFilterWhere(['like', 'groundsign', $this->groundsign])
            ->andFilterWhere(['like', 'farmersign', $this->farmersign])
            ->andFilterWhere(['like', 'pinyin', $this->pinyin])
            ->andFilterWhere(['like', 'farmerpinyin', $this->farmerpinyin])
            ->andFilterWhere(['like', 'contractnumber', $this->contractnumber])
            ->andFilterWhere(['like', 'latitude', $this->latitude])
            ->andFilterWhere(['like', 'longitude', $this->longitude])
            ->andFilterWhere($this->betweenSearch());
          	//->andFilterWhere(['between', 'measure', $this->measure,$this->measure]);

        return $dataProvider;
    }
    public function searchIndex($params)
    {
//     	    	var_dump($params);
//     	       exit;
    	$query = farms::find();
    	//$query->joinWith(['farmer']);
    
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    			'pagination' => [
    					'pageSize' => 0,
    			],
    	]);    
    
//     	$this->load($params);
    
    	if (!$this->validate()) {
    		// uncomment the following line if you do not want to any records when validation fails
    		// $query->where('0=1');
    		return $dataProvider;
    	}
    	if(isset($params['management_area']))
    		$management_area = $params['management_area'];
    	else
    		$management_area = $this->management_area;
    	
    	if(isset($params['farmname']))
    		$farmname = $params['farmname'];
    	else
    		$farmname = $this->farmname;
    	
    	if(isset($params['farmername']))
    		$farmername = $params['farmername'];
    	else
    		$farmername = $this->farmername;
    	
    	if(isset($params['address']))
    		$address = $params['address'];
    	else
    		$address = $this->address;
    	
    	if(isset($params['telephone']))
    		$telephone = $params['telephone'];
    	else
    		$telephone = $this->telephone;
    	
    	$query->andFilterWhere([
    			'id' => $this->id,
    			'locked' => $this->locked,
    			'management_area' => $management_area,
    	]);
    	//$this->management_area = [1, 4, 5];
    
    	$query->andFilterWhere($this->pinyinSearch($farmname))
    	->andFilterWhere($this->farmerpinyinSearch($farmername))
    	
    	->andFilterWhere(['like', 'cardid', $this->cardid])
    	->andFilterWhere(['like', 'telephone', $telephone])
    	->andFilterWhere(['like', 'address', $address])
    	->andFilterWhere(['like', 'state', $this->state])
    	->andFilterWhere(['like', 'oldfarms_id', $this->oldfarms_id])
//     	->andWhere(['management_area' => $this->management_area])
    	->andFilterWhere(['like', 'spyear', $this->spyear])
    	->andFilterWhere(['like', 'zongdi', $this->zongdi])
    	->andFilterWhere(['like', 'notclear', $this->notclear])
    	->andFilterWhere(['like', 'cooperative_id', $this->cooperative_id])
    	->andFilterWhere(['like', 'surveydate', $this->surveydate])
    	->andFilterWhere(['like', 'groundsign', $this->groundsign])
    	->andFilterWhere(['like', 'farmersign', $this->farmersign])
    	->andFilterWhere(['like', 'pinyin', $this->pinyin])
    	->andFilterWhere(['like', 'farmerpinyin', $this->farmerpinyin])
    	->andFilterWhere(['like', 'contractnumber', $this->contractnumber])
    	->andFilterWhere(['like', 'latitude', $this->latitude])
    	->andFilterWhere(['like', 'longitude', $this->longitude])
    	->andFilterWhere(['between','update_at',$params['begindate'],$params['enddate']]);
    	//->andFilterWhere(['between', 'measure', $this->measure,$this->measure]);
    
    	return $dataProvider;
    }
    
    public function ttposearch($params)
    {
    	//     	var_dump($params);
    	//        exit;
    	$query = farms::find();
    	//$query->joinWith(['farmer']);
    
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    	]);

    
    
    	$this->load($params);
    
    	if (!$this->validate()) {
    		// uncomment the following line if you do not want to any records when validation fails
    		// $query->where('0=1');
    		return $dataProvider;
    	}
    	//var_dump($dataProvider);
    	$query->andFilterWhere([
    			'id' => $this->id,
    			//'state' => $this->state,
    	]);
    	//$this->management_area = [1, 4, 5];
    
    	$query->orWhere($this->pinyinSearch())
            ->orWhere($this->farmerpinyinSearch())
            ->andWhere(['state' => $this->state]);
    	//->andFilterWhere(['between', 'measure', $this->measure,$this->measure]);
    
    	return $dataProvider;
    }
}
