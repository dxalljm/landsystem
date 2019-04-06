<?php

namespace frontend\models;

use app\models\User;
use app\models\Farms;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Insurance;
use app\models\Theyear;

/**
 * insuranceSearch represents the model behind the search form about `app\models\Insurance`.
 */
class insuranceSearch extends Insurance
{
    public $select;
//     public $;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','management_area', 'farms_id','state','fwdtstate','issame','isselfselect','isbxsame','iscontractarea','farmstate','company_id'], 'integer'],
            [['wheat', 'soybean', 'insuredarea', 'insuredwheat', 'insuredsoybean','other','insuredother','contractarea','insured'], 'number'],
            [['year','farmername', 'farmerpinyin','policyholder', 'policyholderpinyin','cardid', 'telephone', 'company_id', 'create_at', 'update_at', 'policyholdertime', 'managemanttime', 'halltime','select'], 'string', 'max' => 500],
            [['statecontent'],'string']
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

    public function policyholderSearch($str = NULL)
    {

        $this->policyholder = $str;
        if (preg_match ("/^[A-Za-z]/", $this->policyholder)) {
            $tj = ['like','policyholderpinyin',$this->policyholder];
        } else {
            $tj = ['like','policyholder',$this->policyholder];
        }
//     	var_dump($tj);exit;
        return $tj;
    }

 	public function pinyinSearch($str = NULL)
    {
    	$this->farms_id = $str;
    	if (preg_match ("/^[A-Za-z]/", $this->farms_id)) {
    		$tj = ['like','pinyin',$this->farms_id];
    	} else {
    		$tj = ['like','farmname',$this->farms_id];
    	}
    
    	return $tj;
    }
    
 	public function farmerpinyinSearch($str = NULL)
    {
    	$this->farmername = $str;
    	if (preg_match ("/^[A-Za-z]/", $this->farmername)) {
    		$tj = ['like','farmerpinyin',$this->farmername];
    	} else {
    		$tj = ['like','farmername',$this->farmername];
    	}
    	//     	var_dump($tj);exit;
    	return $tj;
    }

    public function areaSearch($field,$str = NULL)
    {
        $this->$field = $str;
        if(!empty($this->$field)) {
            preg_match_all('/(.*)([0-9]+?)/iU', $this->$field, $where);
            //print_r($where);

            // 		string(2) ">="
            // 		string(3) "300"
            if($where[1][0] == '>' or $where[1][0] == '>=')
                $tj = ['between', $field, (float)$where[2][0],(float)99999.0];
            if($where[1][0] == '<' or $where[1][0] == '<=')
                $tj = ['between', $field, (float)0.0,(float)$where[2][0]];
            if($where[1][0] == '')
                $tj = ['like', $field, $this->$field];
        } else
            $tj = ['like', $field, $this->$field];
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
//        var_dump($params);exit;
        $query = Insurance::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        
        $query->andFilterWhere([
            'id' => $this->id,
            'management_area' => $this->management_area,
            'farms_id' => $this->farms_id,
            'wheat' => $this->wheat,
            'soybean' => $this->soybean,
            'insuredarea' => $this->insuredarea,
            'insuredwheat' => $this->insuredwheat,
            'insuredsoybean' => $this->insuredsoybean,
        	'other' => $this->other,
            'state' => $this->state,
            'fwdtstate' => $this->fwdtstate,
            'issame' => $this->issame,
            'isselfselect' => $this->isselfselect,
        	'company_id' => $this->company_id,
            'farmstate' => $this->farmstate,
        ]);
        if($this->insured) {
            $this->insured = $this->insured . '-';
        }
        $query->andFilterWhere(['like','insured',$this->insured]);
//         var_dump($dataProvider->getModels());exit;
        $query->andFilterWhere(['like', 'year', $this->year])
            ->andFilterWhere(['like', 'farmername', $this->farmername])
            ->andFilterWhere(['like', 'policyholder', $this->policyholder])
            ->andFilterWhere(['like', 'cardid', $this->cardid])
            ->andFilterWhere(['like', 'telephone', $this->telephone])
            ->andFilterWhere(['like', 'contractarea', $this->contractarea])
            ->andFilterWhere(['like', 'create_at', $this->create_at])
            ->andFilterWhere(['like', 'update_at', $this->update_at])
            ->andFilterWhere(['like', 'policyholdertime', $this->policyholdertime])
            ->andFilterWhere(['like', 'managemanttime', $this->managemanttime])
            ->andFilterWhere(['like', 'statecontent', $this->statecontent])

            ->andFilterWhere(['like', 'halltime', $this->halltime]);
//        var_dump($dataProvider->query->where);exit;
        return $dataProvider;
    }

    public function searchIndex($params)
    {
//    	var_dump($params);exit;
        $query = Insurance::find();
// 		var_dump($query->all());
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        if(isset($params['insuranceSearch']['management_area'])) {
            if ($params['insuranceSearch']['management_area'] == 0)
                $this->management_area = Null;
            else
                $this->management_area = $params['insuranceSearch']['management_area'];
        } else {
            $management_area = Farms::getManagementArea()['id'];
			if(count($management_area) > 1)
				$this->management_area = NULL;
			else 
				$this->management_area = $management_area;
        }
//         if(isset($params['insuranceSearch']['state'])) {
//         	$this->state = $params['insuranceSearch']['state'];
//         }
        if(isset($params['insuranceSearch']['fwdtstate'])) {
            $this->fwdtstate = $params['insuranceSearch']['fwdtstate'];
        }
        if(isset($params['insuranceSearch']['isbxsame'])) {
        	$this->state = $params['insuranceSearch']['isbxsame'];
        }
        $farmid = [];
      
        if(isset($params['insuranceSearch']['farms_id']) and $params['insuranceSearch']['farms_id'] !== '') {
        	$farm = Farms::find()->where(['state'=>[1,2,3,4,5]])->andFilterWhere($this->pinyinSearch($params['insuranceSearch']['farms_id']))->select('id');
        }
        if(isset($farm)) { 
        	foreach ($farm->all() as $value) {
        		$farmid[] = $value['id'];
        	}
        }
        if(isset($params['insuranceSearch']['state']))
            $this->state = $params['insuranceSearch']['state'];

//        if(isset($params['insuranceSearch']['insured'])) {
//            $this->insured = $params['insuranceSearch']['insured'];
//            $query->andFilterWhere(['like','insured',$this->insured.'-']);
//        }

        if(isset($params['insuranceSearch']['farmstate']))
            $this->farmstate = $params['insuranceSearch']['farmstate'];
        if(isset($params['insuranceSearch']['company_id'])) {
            $this->company_id = (int)$params['insuranceSearch']['company_id'];
        }
        $query->andFilterWhere([
            'id' => $this->id,
        	'farms_id' => $farmid,
            'management_area' => $this->management_area,
        	'state' => $this->state,
        	'isbxsame' => $this->isbxsame,
            'fwdtstate' => $this->fwdtstate,
            'farmstate' => $this->farmstate,
            'company_id' => $this->company_id,
        ]);

        if(isset($params['insuranceSearch']['insuredarea']))
            $query->andFilterWhere($this->areaSearch('insuredarea',$params['insuranceSearch']['insuredarea']));
        if(isset($params['insuranceSearch']['insuredwheat']))
            $query->andFilterWhere($this->areaSearch('insuredwheat',$params['insuranceSearch']['insuredwheat']));
        if(isset($params['insuranceSearch']['insuredsoybean']))
            $query->andFilterWhere($this->areaSearch('insuredsoybean',$params['insuranceSearch']['insuredsoybean']));
        if(isset($params['insuranceSearch']['insuredother']))
            $query->andFilterWhere($this->areaSearch('insuredother',$params['insuranceSearch']['insuredother']));
        if(isset($params['insuranceSearch']['farmername']))
            $query->andFilterWhere($this->farmerpinyinSearch($params['insuranceSearch']['farmername']));
        if(isset($params['insuranceSearch']['policyholder']))
            $query->andFilterWhere($this->policyholderSearch($params['insuranceSearch']['policyholder']));
        if(isset($params['insuranceSearch']['contractarea']))
            $query->andFilterWhere($this->areaSearch('contractarea',$params['insuranceSearch']['contractarea']));

        if(isset($params['insuranceSearch']['select']) and $params['insuranceSearch']['select'] !== '') {
                        
        	$this->select = $params['insuranceSearch']['select'];
        	switch ($params['insuranceSearch']['select']) {
        		case 'issame':
//                    $farm = Farms::find()->where(['id'=>$this->farms_id])
        			$query->andFilterWhere(['state'=>1,'iscontractarea'=>0])->andWhere('insuredarea<contractarea');
                    break;
        		case 'finished' :
        			$query->andFilterWhere(['state' => 1]);
        			break;
        		case 'cancal':
        			$query->andFilterWhere(['state' => -1]);
        			break;
        		case 'dsh':
        			$query->andFilterWhere(['fwdtstate'=>0]);
        			break;
        		default:
        			$query->andFilterWhere([$params['insuranceSearch']['select']=>0]);
        	}        	            
        }
//        var_dump(User::getYear());exit;
        $query->andFilterWhere(['year'=>User::getYear()]);
//        if(isset($params['begindate']) and isset($params['enddate']))
//        	$query->andFilterWhere(['between','update_at',$params['begindate'],$params['enddate']]);
//        else {
//        	$query->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]]);
//        }
// 		var_dump($dataProvider->query->where);exit;
        return $dataProvider;
    }
    public function searchIndex2($params)
    {
//     	    	var_dump($params);exit;
    	$query = Insurance::find();
    	// 		var_dump($query->all());
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    	]);
    	if(isset($params['insuranceSearch']['management_area'])) {
    		if ($params['insuranceSearch']['management_area'] == 0)
    			$this->management_area = Null;
    		else
    			$this->management_area = $params['insuranceSearch']['management_area'];
    	}
    	if(isset($params['insuranceSearch']['state'])) {
    		$this->state = $params['insuranceSearch']['state'];
    	}
        if(isset($params['insuranceSearch']['fwdtstate'])) {
            $this->fwdtstate = $params['insuranceSearch']['fwdtstate'];
        }
    	if(isset($params['insuranceSearch']['isbxsame'])) {
    		$this->isbxsame = $params['insuranceSearch']['isbxsame'];
    	}
    	$query->andFilterWhere([
    			'id' => $this->id,
    			'management_area' => $this->management_area,
    			'state' => $this->state,
    			'isbxsame' => $this->isbxsame,
                'fwdtstate' => $this->fwdtstate,
    	]);
//     	var_dump($query);exit;
    	//         if(isset($params['insuranceSearch']['state']))
    		//         	$query->andFilterWhere([
    		//                 'state' => $this->state,
    		//             ]);
    	if(isset($params['insuranceSearch']['insuredarea']))
    		$query->andFilterWhere($this->areaSearch('insuredarea',$params['insuranceSearch']['insuredarea']));
    	if(isset($params['insuranceSearch']['insuredwheat']))
    		$query->andFilterWhere($this->areaSearch('insuredwheat',$params['insuranceSearch']['insuredwheat']));
    	if(isset($params['insuranceSearch']['insuredsoybean']))
    		$query->andFilterWhere($this->areaSearch('insuredsoybean',$params['insuranceSearch']['insuredsoybean']));
    	if(isset($params['insuranceSearch']['insuredother']))
    		$query->andFilterWhere($this->areaSearch('insuredother',$params['insuranceSearch']['insuredother']));
    
    	if(isset($params['insuranceSearch']['company_id'])) {
    		$this->company_id = $params['insuranceSearch']['company_id'];
    		$query->andFilterWhere([
    				'company_id' => $this->company_id,
    		]);
    	}
    	if(isset($params['insuranceSearch']['farmername']))
    		$query->andFilterWhere($this->farmerpinyinSearch($params['insuranceSearch']['farmername']));
    	if(isset($params['insuranceSearch']['policyholder']))
    		$query->andFilterWhere($this->policyholderSearch($params['insuranceSearch']['policyholder']));
    	if(isset($params['insuranceSearch']['contractarea']))
    		$query->andFilterWhere($this->areaSearch('contractarea',$params['insuranceSearch']['contractarea']));
    
    	if(isset($params['insuranceSearch']['select']) and $params['insuranceSearch']['select'] !== '') {
    
    		$this->select = $params['insuranceSearch']['select'];
    		switch ($params['insuranceSearch']['select']) {
    			case 'finished' :
    				$query->andFilterWhere(['state' => 1]);
    				break;
    			case 'cancal':
    				$query->andFilterWhere(['state' => -1]);
    				break;
    			case 'dsh':
    				$query->andFilterWhere(['fwdtstate'=>0]);
    				break;
    			default:
    				$query->andFilterWhere([$params['insuranceSearch']['select']=>0]);
    		}
    	}
        $query->andFilterWhere(['year'=>User::getYear()]);
    	//        var_dump($this->farmername);exit;
    
    	return $dataProvider;
    }

    public function searchSearch($params)
    {
//     	var_dump($params);exit;
        $query = Insurance::find();
// 		var_dump($query->all());
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        if(isset($params['insuranceSearch']['management_area'])) {
            $this->management_area = $params['insuranceSearch']['management_area'];
        } else {
            $this->management_area = Farms::getManagementArea()['id'];
        }

        if(count($this->management_area) > 1)
            $this->management_area = NULL;

        if(isset($params['insuranceSearch']['state'])) {
            $this->state = $params['insuranceSearch']['state'];
        }
        if(isset($params['insuranceSearch']['fwdtstate'])) {
            $this->fwdtstate = $params['insuranceSearch']['fwdtstate'];
        }
        if(isset($params['insuranceSearch']['isbxsame'])) {
            $this->state = $params['insuranceSearch']['isbxsame'];
        }
        if(isset($params['insuranceSearch']['year'])) {
        	$this->year = $params['insuranceSearch']['year'];
        }
        if(isset($params['insuranceSearch']['company_id'])) {
            $this->company_id = $params['insuranceSearch']['company_id'];
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'management_area' => $this->management_area,
            'state' => $this->state,
            'isbxsame' => $this->isbxsame,
            'fwdtstate' => $this->fwdtstate,
        	'year' => $this->year,
            'company_id' => $this->company_id,
        ]);
//         if(isset($params['insuranceSearch']['state']))
//         	$query->andFilterWhere([
//                 'state' => $this->state,
//             ]);
        if(isset($params['insuranceSearch']['insuredarea']))
            $query->andFilterWhere($this->areaSearch('insuredarea',$params['insuranceSearch']['insuredarea']));
        if(isset($params['insuranceSearch']['insuredwheat']))
            $query->andFilterWhere($this->areaSearch('insuredwheat',$params['insuranceSearch']['insuredwheat']));
        if(isset($params['insuranceSearch']['insuredsoybean']))
            $query->andFilterWhere($this->areaSearch('insuredsoybean',$params['insuranceSearch']['insuredsoybean']));
        if(isset($params['insuranceSearch']['insuredother']))
            $query->andFilterWhere($this->areaSearch('insuredother',$params['insuranceSearch']['insuredother']));
        if(isset($params['insuranceSearch']['fwdtstate']))
            $query->andFilterWhere(['fwdtstate'=>$params['insuranceSearch']['fwdtstate']]);

        if(isset($params['insuranceSearch']['farmername']))
            $query->andFilterWhere($this->farmerpinyinSearch($params['insuranceSearch']['farmername']));
        if(isset($params['insuranceSearch']['policyholder']))
            $query->andFilterWhere($this->policyholderSearch($params['insuranceSearch']['policyholder']));
        if(isset($params['insuranceSearch']['contractarea']))
            $query->andFilterWhere($this->areaSearch('contractarea',$params['insuranceSearch']['contractarea']));

        if(isset($params['insuranceSearch']['select']) and $params['insuranceSearch']['select'] !== '') {

            $this->select = $params['insuranceSearch']['select'];
            switch ($params['insuranceSearch']['select']) {
                case 'finished' :
                    $query->andFilterWhere(['state' => 1]);
                    break;
                case 'cancal':
                    $query->andFilterWhere(['state' => -1]);
                    break;
                case 'dsh':
                    $query->andFilterWhere(['fwdtstate'=>0]);
                    break;
                default:
                    $query->andFilterWhere([$params['insuranceSearch']['select']=>0]);
            }
        }
//        var_dump(User::getYear());exit;
//        $query->andFilterWhere(['year'=>User::getYear()]);
        if(isset($params['begindate']) and isset($params['enddate']))
        	$query->andFilterWhere(['between','update_at',$params['begindate'],$params['enddate']]);
        else {
        	$query->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]]);
        }
//		var_dump($dataProvider->query->where);exit;
        return $dataProvider;
    }
}
