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
            [['id', 'farms_id','management_area','farmer_id'], 'integer'],
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
    public function search($params)
    {
        $query = Loan::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'farms_id' => $this->farms_id,
            'mortgagearea' => $this->mortgagearea,
            'mortgagemoney' => $this->mortgagemoney,
        ]);

        $query->andFilterWhere(['like', 'mortgagebank', $this->mortgagebank])
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
    public function searchIndex($params)
    {
    	$query = Loan::find();
    
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    	]);
    
    	if($params['loanSearch']['management_area'] == 0)
    		$this->management_area = NULL;
    	else
    		$this->management_area = $params['loanSearch']['management_area'];
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
    	
    	if(isset($params['loanSearch']['mortgagemoney']) and $params['loanSearch']['mortgagemoney'] !== '') {
    		$query->andFilterWhere($this->numberSearch('mortgagemoney',$params['loanSearch']['mortgagemoney']));
    	}
    	
    	if(isset($params['loanSearch']['mortgagearea']) and $params['loanSearch']['mortgagearea'] !== '') {
    		$query->andFilterWhere($this->numberSearch('mortgagearea',$params['loanSearch']['mortgagearea']));
    	}
    	$query->andFilterWhere([
    			'id' => $this->id,
    			'farms_id' => $farmid,
//     			'mortgagearea' => $this->mortgagearea,
    			'mortgagebank' => $this->mortgagebank,
//     			'mortgagemoney' => $this->mortgagemoney,
    	]);
    
//     	$query->andFilterWhere(['like', 'mortgagebank', $this->mortgagebank])
    	$query->andFilterWhere(['between','update_at',$params['begindate'],$params['enddate']]);
    
    
    	return $dataProvider;
    }
}
