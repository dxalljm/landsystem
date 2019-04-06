<?php

namespace frontend\models;

use console\models\Loan;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Reviewprocess;
use app\models\Farms;
use app\models\User;

/**
 * ReviewprocessSearch represents the model behind the search form about `app\models\Reviewprocess`.
 */
class ReviewprocesslSearch extends Reviewprocess
{
	public $farmname;
	public $farmername;
	public $contractarea;

    public $oldfarmname;
    public $oldfarmername;
    public $oldcontractarea;

    public $loanarea;
    public $loanmoney;
    public $loanbank;
    public $loanfarmname;
    public $loanfarmername;
    public $loancontractarea;
    /**
     * @inheritdoc
     */
public function rules()
    {
        return [
            [['id', 'newfarms_id','operation_id', 'create_at', 'update_at', 'estate', 'finance', 'filereview', 'publicsecurity', 'leader', 'mortgage', 'steeringgroup', 'estatetime', 'financetime', 'filereviewtime', 'publicsecuritytime', 'leadertime', 'mortgagetime', 'steeringgrouptime', 'regulations', 'regulationstime', 'oldfarms_id', 'management_area', 'state', 'project', 'projecttime','ttpozongdi_id'], 'integer'],
            [['estatecontent', 'financecontent', 'filereviewcontent', 'publicsecuritycontent', 'leadercontent', 'mortgagecontent', 'steeringgroupcontent', 'regulationscontent', 'actionname', 'projectcontent'], 'safe'],
        	[['farmname','farmername','oldfarmname','oldfarmername','loanbank','loanfarmname','loanfarmername'],'string'],
        	[['contractarea','oldcontractarea','loanarea','loanmoney','loancontractarea'],'number'],
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

    
    public function contractareaSearch($str = NULL)
    {
    	$tj = [];
    	if(!empty($str)) {
    		preg_match_all('/(.*)([0-9]+?)/iU', $str, $where);
    		// 		string(2) ">="
    		// 		string(3) "300"
    		if($where[1][0] == '>' or $where[1][0] == '>=')
    			$tj = ['between', 'contractarea', (float)$where[2][0],(float)99999.0];
    		if($where[1][0] == '<' or $where[1][0] == '<=')
    			$tj = ['between', 'contractarea', (float)0.0,(float)$where[2][0]];
    		if($where[1][0] == '')
    			$tj = ['like', 'contractarea', $str];
    	} else
    		$tj = ['like', 'contractarea', $str];
    	return $tj;
    }
    public function loanareaSearch($str = NULL)
    {
        $tj = [];
        if(!empty($str)) {
            preg_match_all('/(.*)([0-9]+?)/iU', $str, $where);
            // 		string(2) ">="
            // 		string(3) "300"
            if($where[1][0] == '>' or $where[1][0] == '>=')
                $tj = ['between', 'mortgagearea', (float)$where[2][0],(float)99999.0];
            if($where[1][0] == '<' or $where[1][0] == '<=')
                $tj = ['between', 'mortgagearea', (float)0.0,(float)$where[2][0]];
            if($where[1][0] == '')
                $tj = ['like', 'mortgagearea', $str];
        } else
            $tj = ['like', 'mortgagearea', $str];
        return $tj;
    }
    public function loanmoneySearch($str = NULL)
    {
        $tj = [];
        if(!empty($str)) {
            preg_match_all('/(.*)([0-9]+?)/iU', $str, $where);
            // 		string(2) ">="
            // 		string(3) "300"
            if($where[1][0] == '>' or $where[1][0] == '>=')
                $tj = ['between', 'mortgagemoney', (float)$where[2][0],(float)99999.0];
            if($where[1][0] == '<' or $where[1][0] == '<=')
                $tj = ['between', 'mortgagemoney', (float)0.0,(float)$where[2][0]];
            if($where[1][0] == '')
                $tj = ['like', 'mortgagemoney', $str];
        } else
            $tj = ['like', 'mortgagemoney', $str];
        return $tj;
    }
    public function pinyinSearch($str = NULL)
    {
        $tj = [];
        if(!empty($str)) {
            if (preg_match("/^[A-Za-z]/", $str)) {
                $tj = ['like', 'pinyin', $str];
            } else {
                $tj = ['like', 'farmname', $str];
            }
        }
//        var_dump($tj);exit;
    	return $tj;
    }
    
    public function farmerpinyinSearch($str = NULL)
    {
        $tj = [];
        if(!empty($str)) {
            if (preg_match("/^[A-Za-z]/", $str)) {
                $tj = ['like', 'farmerpinyin', $str];
            } else {
                $tj = ['like', 'farmername', $str];
            }
        }
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
        $query = Reviewprocess::find()->orderBy('id desc');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if(is_array($this->management_area) and count($this->management_area) > 1) {
            $this->management_area = null;
        }

        if($this->farmname !== null or $this->farmername !== null or $this->contractarea !== null) {
            var_dump('222');
            $farm = Farms::find();
            $farm->andFilterWhere($this->pinyinSearch($this->farmname));
            $farm->andFilterWhere($this->farmerpinyinSearch($this->farmername));
            $farm->andFilterWhere($this->contractareaSearch($this->contractarea));
            var_dump($farm->where);exit;
        }

        if($this->oldfarmname !== null or $this->oldfarmername !== null or $this->oldcontractarea !== null) {
            var_dump('333');
            $farm = Farms::find();
            $farm->andFilterWhere($this->pinyinSearch($this->oldfarmname));
            $farm->andFilterWhere($this->farmerpinyinSearch($this->oldfarmername));
            $farm->andFilterWhere($this->contractareaSearch($this->oldcontractarea));
        }
        if(isset($farm)) {
// 			var_dump($farm->all());exit;
            $farmid = [];
            foreach ($farm->all() as $value) {
                $farmid[] = $value['id'];
            }
        }
        if(isset($farmid)) {
            $this->newfarms_id = $farmid;
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'newfarms_id' => $this->newfarms_id,
        	'operation_id' => $this->operation_id,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
            'estate' => $this->estate,
            'finance' => $this->finance,
            'filereview' => $this->filereview,
            'publicsecurity' => $this->publicsecurity,
            'leader' => $this->leader,
            'mortgage' => $this->mortgage,
            'steeringgroup' => $this->steeringgroup,
            'estatetime' => $this->estatetime,
            'financetime' => $this->financetime,
            'filereviewtime' => $this->filereviewtime,
            'publicsecuritytime' => $this->publicsecuritytime,
            'leadertime' => $this->leadertime,
            'mortgagetime' => $this->mortgagetime,
            'steeringgrouptime' => $this->steeringgrouptime,
            'regulations' => $this->regulations,
            'regulationstime' => $this->regulationstime,
            'oldfarms_id' => $this->oldfarms_id,
            'management_area' => $this->management_area,
            'state' => $this->state,
            'project' => $this->project,
            'projecttime' => $this->projecttime,
        ]);

        $query->andFilterWhere(['like', 'estatecontent', $this->estatecontent])
            ->andFilterWhere(['like', 'financecontent', $this->financecontent])
            ->andFilterWhere(['like', 'filereviewcontent', $this->filereviewcontent])
            ->andFilterWhere(['like', 'publicsecuritycontent', $this->publicsecuritycontent])
            ->andFilterWhere(['like', 'leadercontent', $this->leadercontent])
            ->andFilterWhere(['like', 'mortgagecontent', $this->mortgagecontent])
            ->andFilterWhere(['like', 'steeringgroupcontent', $this->steeringgroupcontent])
            ->andFilterWhere(['like', 'regulationscontent', $this->regulationscontent])
            ->andFilterWhere(['like', 'actionname', $this->actionname])
            ->andFilterWhere(['like', 'projectcontent', $this->projectcontent])
            ->andFilterWhere(['between','create_at',$params['begindate'],$params['enddate']]);

//        var_dump($dataProvider->query->where);exit;
        return $dataProvider;
    }

    public function searchLoan($params)
    {
//        var_dump($params);exit;
        $query = Reviewprocess::find()->orderBy('id desc');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if(isset($params['ReviewprocesslSearch']['management_area'])) {
            $this->management_area = $params['ReviewprocesslSearch']['management_area'];
        }
        if(is_array($this->management_area) and count($this->management_area) > 1) {
            $this->management_area = null;
        }
//        var_dump($this->management_area);exit;
        $farm = Farms::find();
        if(isset($params['ReviewprocesslSearch']['loanfarmname'])) {
            $this->loanfarmname = $params['ReviewprocesslSearch']['loanfarmname'];
        }
        if(isset($params['ReviewprocesslSearch']['loanfarmername'])) {
            $this->loanfarmername = $params['ReviewprocesslSearch']['loanfarmername'];
        }
        if(isset($params['ReviewprocesslSearch']['loancontractarea'])) {
            $this->loancontractarea = $params['ReviewprocesslSearch']['loancontractarea'];
        }
        if(isset($params['ReviewprocesslSearch']['loanfarmname'])) {
            $this->loanfarmname = $params['ReviewprocesslSearch']['loanfarmname'];
        }
        if(isset($params['ReviewprocesslSearch']['loanfarmername'])) {
            $this->loanfarmername = $params['ReviewprocesslSearch']['loanfarmername'];
        }
        if(isset($params['ReviewprocesslSearch']['loancontractarea'])) {
            $this->loancontractarea = $params['ReviewprocesslSearch']['loancontractarea'];
        }
        if(!empty($this->loanfarmname) or !empty($this->loanfarmername) or !empty($this->loancontractarea)) {
            $farm->andFilterWhere($this->pinyinSearch($this->loanfarmname));
            $farm->andFilterWhere($this->farmerpinyinSearch($this->loanfarmername));
            $farm->andFilterWhere($this->contractareaSearch($this->loancontractarea));
        }
        $loan = Loan::find()->where(['year'=>User::getYear()]);
        if(!empty($this->loanarea) or !empty($this->loanmoney) or !empty($this->loanbank)) {
            $loan->andFilterWhere($this->loanareaSearch($this->loanarea));
            $loan->andFilterWhere($this->loanmoneySearch($this->loanmoney));
            $loan->andFilterWhere(['mortgagebank'=>$this->loanbank]);
        }
        if(!empty($loan->where)) {
            $loanid = [];
            foreach ($loan->all() as $value) {
                $loanid[] = $value['farms_id'];
            }
        }

        if(!empty($farm->where)) {
            $farmid = [];
            foreach ($farm->all() as $value) {
                $farmid[] = $value['id'];
            }
        }
        if(isset($farmid) and !empty($farmid)) {
            $this->oldfarms_id = $farmid;
        } else {
            $this->oldfarms_id = 0;
        }
        if(isset($loanid) and !empty($loanid)) {
            $this->oldfarms_id = $loanid;
        } else {
            $this->oldfarms_id = 0;
        }
        if(!empty($loanid) and !empty($farmid)) {
            $this->oldfarms_id = array_intersect($loanid,$farmid);
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'newfarms_id' => $this->newfarms_id,
            'operation_id' => $this->operation_id,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
            'estate' => $this->estate,
            'finance' => $this->finance,
            'filereview' => $this->filereview,
            'publicsecurity' => $this->publicsecurity,
            'leader' => $this->leader,
            'mortgage' => $this->mortgage,
            'steeringgroup' => $this->steeringgroup,
            'estatetime' => $this->estatetime,
            'financetime' => $this->financetime,
            'filereviewtime' => $this->filereviewtime,
            'publicsecuritytime' => $this->publicsecuritytime,
            'leadertime' => $this->leadertime,
            'mortgagetime' => $this->mortgagetime,
            'steeringgrouptime' => $this->steeringgrouptime,
            'regulations' => $this->regulations,
            'regulationstime' => $this->regulationstime,
            'oldfarms_id' => $this->oldfarms_id,
            'management_area' => $this->management_area,
            'state' => $this->state,
            'project' => $this->project,
            'projecttime' => $this->projecttime,
            'ttpozongdi_id' => $this->ttpozongdi_id,
        ]);

        $query->andFilterWhere(['like', 'estatecontent', $this->estatecontent])
            ->andFilterWhere(['like', 'financecontent', $this->financecontent])
            ->andFilterWhere(['like', 'filereviewcontent', $this->filereviewcontent])
            ->andFilterWhere(['like', 'publicsecuritycontent', $this->publicsecuritycontent])
            ->andFilterWhere(['like', 'leadercontent', $this->leadercontent])
            ->andFilterWhere(['like', 'mortgagecontent', $this->mortgagecontent])
            ->andFilterWhere(['like', 'steeringgroupcontent', $this->steeringgroupcontent])
            ->andFilterWhere(['like', 'regulationscontent', $this->regulationscontent])
            ->andFilterWhere(['like', 'actionname', $this->actionname])
            ->andFilterWhere(['like', 'projectcontent', $this->projectcontent])
            ->andFilterWhere(['between','update_at',$params['begindate'],$params['enddate']]);

//        var_dump($dataProvider->query->where);exit;
        return $dataProvider;
    }

    public function searchLoanunlock($params)
    {
//        var_dump($params);exit;
        $query = Reviewprocess::find()->orderBy('id desc');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if(isset($params['ReviewprocesslSearch']['management_area'])) {
            $this->management_area = $params['ReviewprocesslSearch']['management_area'];
        }
        if(is_array($this->management_area) and count($this->management_area) > 1) {
            $this->management_area = null;
        }
//        var_dump($this->management_area);exit;
        $farm = Farms::find();
        if(isset($params['ReviewprocesslSearch']['loanfarmname'])) {
            $this->loanfarmname = $params['ReviewprocesslSearch']['loanfarmname'];
        }
        if(isset($params['ReviewprocesslSearch']['loanfarmername'])) {
            $this->loanfarmername = $params['ReviewprocesslSearch']['loanfarmername'];
        }
        if(isset($params['ReviewprocesslSearch']['loancontractarea'])) {
            $this->loancontractarea = $params['ReviewprocesslSearch']['loancontractarea'];
        }
        if(isset($params['ReviewprocesslSearch']['loanfarmname'])) {
            $this->loanfarmname = $params['ReviewprocesslSearch']['loanfarmname'];
        }
        if(isset($params['ReviewprocesslSearch']['loanfarmername'])) {
            $this->loanfarmername = $params['ReviewprocesslSearch']['loanfarmername'];
        }
        if(isset($params['ReviewprocesslSearch']['loancontractarea'])) {
            $this->loancontractarea = $params['ReviewprocesslSearch']['loancontractarea'];
        }
        if(!empty($this->loanfarmname) or !empty($this->loanfarmername) or !empty($this->loancontractarea)) {
            $farm->andFilterWhere($this->pinyinSearch($this->loanfarmname));
            $farm->andFilterWhere($this->farmerpinyinSearch($this->loanfarmername));
            $farm->andFilterWhere($this->contractareaSearch($this->loancontractarea));
        }
        $loan = Loan::find();
        if(!empty($this->loanarea) or !empty($this->loanmoney) or !empty($this->loanbank)) {
            $loan->andFilterWhere($this->loanareaSearch($this->loanarea));
            $loan->andFilterWhere($this->loanmoneySearch($this->loanmoney));
            $loan->andFilterWhere(['mortgagebank'=>$this->loanbank]);
        }
        if(!empty($loan->where)) {
            $loanid = [];
            foreach ($loan->all() as $value) {
                $loanid[] = $value['farms_id'];
            }
        }

        if(!empty($farm->where)) {
            $farmid = [];
            foreach ($farm->all() as $value) {
                $farmid[] = $value['id'];
            }
        }
        if(isset($farmid) and !empty($farmid)) {
            $this->oldfarms_id = $farmid;
        } else {
            $this->oldfarms_id = 0;
        }
        if(isset($loanid) and !empty($loanid)) {
            $this->oldfarms_id = $loanid;
        } else {
            $this->oldfarms_id = 0;
        }
        if(!empty($loanid) and !empty($farmid)) {
            $this->oldfarms_id = array_intersect($loanid,$farmid);
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'newfarms_id' => $this->newfarms_id,
            'operation_id' => $this->operation_id,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
            'estate' => $this->estate,
            'finance' => $this->finance,
            'filereview' => $this->filereview,
            'publicsecurity' => $this->publicsecurity,
            'leader' => $this->leader,
            'mortgage' => $this->mortgage,
            'steeringgroup' => $this->steeringgroup,
            'estatetime' => $this->estatetime,
            'financetime' => $this->financetime,
            'filereviewtime' => $this->filereviewtime,
            'publicsecuritytime' => $this->publicsecuritytime,
            'leadertime' => $this->leadertime,
            'mortgagetime' => $this->mortgagetime,
            'steeringgrouptime' => $this->steeringgrouptime,
            'regulations' => $this->regulations,
            'regulationstime' => $this->regulationstime,
            'oldfarms_id' => $this->oldfarms_id,
            'management_area' => $this->management_area,
            'state' => $this->state,
            'project' => $this->project,
            'projecttime' => $this->projecttime,
            'ttpozongdi_id' => $this->ttpozongdi_id,
        ]);

        $query->andFilterWhere(['like', 'estatecontent', $this->estatecontent])
            ->andFilterWhere(['like', 'financecontent', $this->financecontent])
            ->andFilterWhere(['like', 'filereviewcontent', $this->filereviewcontent])
            ->andFilterWhere(['like', 'publicsecuritycontent', $this->publicsecuritycontent])
            ->andFilterWhere(['like', 'leadercontent', $this->leadercontent])
            ->andFilterWhere(['like', 'mortgagecontent', $this->mortgagecontent])
            ->andFilterWhere(['like', 'steeringgroupcontent', $this->steeringgroupcontent])
            ->andFilterWhere(['like', 'regulationscontent', $this->regulationscontent])
            ->andFilterWhere(['like', 'actionname', $this->actionname])
            ->andFilterWhere(['like', 'projectcontent', $this->projectcontent]);

//        var_dump($dataProvider->query->where);exit;
        return $dataProvider;
    }
}
