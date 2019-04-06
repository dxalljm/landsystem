<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Loan;
use app\models\Theyear;
use app\models\Farms;
/**
 * loanSearch represents the model behind the search form about `app\models\Loan`.
 */
class loanSearch extends Loan
{
	public $farmer_id;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'farms_id','management_area','farmer_id','state','reviewproccess_id','lock','farmstate','year'], 'integer'],
            [['mortgagearea', 'mortgagemoney'], 'number'],
            [['mortgagebank'], 'safe'],
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
    
    public function searchone($params)
    {
    	//     	var_dump($params);exit;
    	$query = Loan::find();
    
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    	]);
    
    	$this->load($params);
    
    	$query->andFilterWhere([
    			'id' => $this->id,
    			'farms_id' => $this->farms_id,
    			'mortgagearea' => $this->mortgagearea,
    			'mortgagemoney' => $this->mortgagemoney,
    			'state' => $this->state,
    			'lock' => $this->lock,
    			'year' => $this->year,
    			'management_area' => $this->management_area,
    	]);
    
    	$query->andFilterWhere(['like', 'mortgagebank', $this->mortgagebank]);
//    	->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]]);
    
    	//		var_dump($dataProvider->query->where);exit;
    	return $dataProvider;
    }
    
    public function search($params)
    {
//     	var_dump($params);exit;
        $query = Loan::find()->orderBy('update_at DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        
        if(is_array($this->management_area) and count($this->management_area)>1) {
        	$this->management_area = NULL;
        }
//         var_dump($this->farmer_id);var_dump($this->farms_id);
        $farmid = [];
       
        if(!empty($this->farms_id)) {
        	$farm = Farms::find();
        	$farm->andFilterWhere(['management_area'=>$this->management_area]);
        	$farm->andFilterWhere($this->pinyinSearch($this->farms_id));
        
        }
        
        if(!empty($this->farmer_id)) {
        	$farm = Farms::find();
        	$farm->andFilterWhere(['management_area'=>$this->management_area]);
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
            'mortgagearea' => $this->mortgagearea,
            'mortgagemoney' => $this->mortgagemoney,
        	'state' => $this->state,
            'lock' => $this->lock,
        	'year' => $this->year,
			'farmstate' => $this->farmstate,
            'management_area' => $this->management_area,
        ]);

        $query->andFilterWhere(['like', 'mortgagebank', $this->mortgagebank]);
//         ->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]]);
            
// 		var_dump($dataProvider->query->where);exit;
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
    public function searchIndex($params)
    {
//        var_dump($params);exit;
    	$query = Loan::find()->where(['state'=>1]);
    
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    	]);

    	if(isset($params['loanSearch']['management_area'])) {
			if($params['loanSearch']['management_area'] == 0)
				$this->management_area = NULL;
			else
				$this->management_area = $params['loanSearch']['management_area'];
		} else {
			$management_area = Farms::getManagementArea()['id'];
			if(count($management_area) > 1)
				$this->management_area = NULL;
			else
				$this->management_area = $management_area;
		}
    	$farmid = [];
    	if((isset($params['loanSearch']['farms_id']) and $params['loanSearch']['farms_id'] !== '') or (isset($params['loanSearch']['farmer_id']) and $params['loanSearch']['farmer_id'] !== '')) {
	    	$farm = Farms::find();
	    	$farm->andFilterWhere(['management_area'=>$this->management_area]);
    	}
    	if(isset($params['loanSearch']['farms_id']) and $params['loanSearch']['farms_id'] !== '') {
    		$this->farms_id = $params['loanSearch']['farms_id'];
    		$farm->andFilterWhere($this->pinyinSearch($this->farms_id));
    		    		
    	}

    	if(isset($params['loanSearch']['farmer_id']) and $params['loanSearch']['farmer_id'] !== '') {
    		$this->farmer_id = $params['loanSearch']['farmer_id'];
    		$farm->andFilterWhere($this->farmerpinyinSearch($this->farmer_id));
    	}
    	if(isset($farm)) {
	    	foreach ($farm->all() as $value) {
	    		$farmid[] = $value['id'];
	    	}
    	}
    	
    	if(isset($params['loanSearch']['mortgagebank']) and $params['loanSearch']['mortgagebank'] !== '') {
    		$this->mortgagebank = $params['loanSearch']['mortgagebank'];
    	}

        if(isset($params['loanSearch']['lock']) and $params['loanSearch']['lock'] !== '') {
            $this->lock = $params['loanSearch']['lock'];
        }

		if(isset($params['loanSearch']['farmstate']) and $params['loanSearch']['farmstate'] !== '') {
			$this->farmstate = $params['loanSearch']['farmstate'];
		}
    	
    	if(isset($params['loanSearch']['mortgagemoney']) and $params['loanSearch']['mortgagemoney'] !== '') {
    		$query->andFilterWhere($this->numberSearch('mortgagemoney',$params['loanSearch']['mortgagemoney']));
    	}
    	
    	if(isset($params['loanSearch']['mortgagearea']) and $params['loanSearch']['mortgagearea'] !== '') {
    		$query->andFilterWhere($this->numberSearch('mortgagearea',$params['loanSearch']['mortgagearea']));
    	}
    	$query->andFilterWhere([
    			'id' => $this->id,
    			'farms_id' => $farmid,
    			'management_area' => $this->management_area,
				'reviewprocess_id' => $this->reviewprocess_id,
    			'mortgagebank' => $this->mortgagebank,
                'lock' => $this->lock,
				'farmstate' => $this->farmstate,
//     			'mortgagemoney' => $this->mortgagemoney,
    	]);
    
//     	$query->andFilterWhere(['like', 'mortgagebank', $this->mortgagebank])
    	if(isset($params['begindate']))
    		$query->andFilterWhere(['between','create_at',$params['begindate'],$params['enddate']]);
		else
			$query->andFilterWhere(['between','create_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]]);
    
    
    	return $dataProvider;
    }

	public function searchUnlock($params)
	{
//        var_dump($params);exit;
		$query = Loan::find();

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		if(isset($params['loanSearch']['management_area'])) {
			$this->management_area = $params['loanSearch']['management_area'];
		}
		if(is_array($this->management_area) and count($this->management_area) > 1) {
			$this->management_area = null;
		}
		$farmid = [];
		if((isset($params['loanSearch']['farms_id']) and $params['loanSearch']['farms_id'] !== '') or (isset($params['loanSearch']['farmer_id']) and $params['loanSearch']['farmer_id'] !== '')) {
			$farm = Farms::find();
			$farm->andFilterWhere(['management_area'=>$this->management_area]);
		}
		if(isset($params['loanSearch']['farms_id']) and $params['loanSearch']['farms_id'] !== '') {
			$this->farms_id = $params['loanSearch']['farms_id'];
			$farm->andFilterWhere($this->pinyinSearch($this->farms_id));

		}

		if(isset($params['loanSearch']['farmer_id']) and $params['loanSearch']['farmer_id'] !== '') {
			$this->farmer_id = $params['loanSearch']['farmer_id'];
			$farm->andFilterWhere($this->farmerpinyinSearch($this->farmer_id));
		}
		if(isset($farm)) {
			foreach ($farm->all() as $value) {
				$farmid[] = $value['id'];
			}
		}

		if(isset($params['loanSearch']['mortgagebank']) and $params['loanSearch']['mortgagebank'] !== '') {
			$this->mortgagebank = $params['loanSearch']['mortgagebank'];
		}

		if(isset($params['loanSearch']['lock']) and $params['loanSearch']['lock'] !== '') {
			$this->lock = $params['loanSearch']['lock'];
		}

		if(isset($params['loanSearch']['mortgagemoney']) and $params['loanSearch']['mortgagemoney'] !== '') {
			$query->andFilterWhere($this->numberSearch('mortgagemoney',$params['loanSearch']['mortgagemoney']));
		}

		if(isset($params['loanSearch']['mortgagearea']) and $params['loanSearch']['mortgagearea'] !== '') {
			$query->andFilterWhere($this->numberSearch('mortgagearea',$params['loanSearch']['mortgagearea']));
		}
		$query->andFilterWhere([
			'id' => $this->id,
			'farms_id' => $farmid,
			'management_area' => $this->management_area,
			'reviewprocess_id' => $this->reviewprocess_id,
			'mortgagebank' => $this->mortgagebank,
			'lock' => $this->lock,
//     			'mortgagemoney' => $this->mortgagemoney,
		]);

//     	$query->andFilterWhere(['like', 'mortgagebank', $this->mortgagebank])


		return $dataProvider;
	}
}
