<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\farms;
use app\models\ManagementArea;
use yii\db\Query;

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
            [['farmname', 'farmername', 'address','measure','notstateinfo', 'management_area','telephone', 'spyear', 'zongdi', 'cooperative_id','notclear','surveydate', 'groundsign', 'farmersign', 'pinyin','farmerpinyin','contractnumber', 'begindate', 'enddate','latitude','longitude'], 'safe'],
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
    
    public function measureSearch($str = NULL)
    {
    	$this->measure = $str;
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
    	
    	$this->farmname = $str;
    	if (preg_match ("/^[A-Za-z]/", $this->farmname)) {
    		$tj = ['like','pinyin',$this->farmname];
    	} else {
    		$tj = ['like','farmname',$this->farmname];
    	}
//     	var_dump($tj);exit;
		return $tj;
    }
    
    public function farmerpinyinSearch($str = NULL)
    {
//     	var_dump($str);exit;
    	$this->farmername = $str;
    	if (preg_match ("/^[A-Za-z]/", $this->farmername)) {
    		$tj = ['like','farmerpinyin',$this->farmername];
    	} else {
    		$tj = ['like','farmername',$this->farmername];
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
		
//         var_dump($dataProvider);exit;
     	$this->load($params);

//      	$this->management_area = 6;
//         var_dump($dataProvider);

        $query->andFilterWhere([
            'id' => $this->id,
        	'locked' => $this->locked,
        	'state' => $this->state,
        	'management_area' => $this->management_area,
//         	'notstate' => $this->notstate,
        ]);
        

          $query->andFilterWhere($this->pinyinSearch($this->farmname))
            ->andFilterWhere($this->farmerpinyinSearch($this->farmername))
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
//     	    	var_dump($params);exit;
//     	       exit;
    	$query = farms::find();
    	//$query->joinWith(['farmer']);
    
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    	]);    
//     	if(isset($params['farmsSearch']['management_area'])) {
    		if($params['farmsSearch']['management_area'] == 0)
    			$this->management_area = NULL;
    		else
    			$this->management_area = $params['farmsSearch']['management_area'];
//     	} 
    	$query->andFilterWhere(['management_area' => $this->management_area]);
    	if(isset($params['farmsSearch']['state']))
    		$query->andFilterWhere(['state' => $params['farmsSearch']['state']]);
    	
    	if(isset($params['farmsSearch']['farmname']))
    		$query->andFilterWhere($this->pinyinSearch($params['farmsSearch']['farmname']));
    	if(isset($params['farmsSearch']['farmername']))
    		$query->andFilterWhere($this->farmerpinyinSearch($params['farmsSearch']['farmername']));
    	
    	if(isset($params['farmsSearch']['telephone']))
    		$this->telephone = $params['farmsSearch']['telephone'];
    	$query->andFilterWhere(['like','telephone',$this->telephone]);
    	if(isset($params['farmsSearch']['address']))
    		$this->address = $params['farmsSearch']['address'];
    		$query->andFilterWhere(['like','address',$this->address]);
    	if(isset($params['farmsSearch']['update_at']))
    		$query->andFilterWhere(['between','update_at',$params['begindate'],$params['enddate']]);
    	if(isset($params['farmsSearch']['measure']))
    		$query->andFilterWhere($this->measureSearch($params['farmsSearch']['measure']));

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
