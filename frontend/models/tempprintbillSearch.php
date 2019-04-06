<?php

namespace frontend\models;

use app\models\Theyear;
use app\models\User;
use app\models\Farms;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Tempprintbill;

/**
 * tempprintbillSearch represents the model behind the search form about `app\models\Tempprintbill`.
 */
class tempprintbillSearch extends Tempprintbill
{
    public $contract;
    public $contractnumber;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','state','farms_id','management_area','contract','farmstate'], 'integer'],
            [['farmername','remarks', 'nonumber','bigamountofmoney','amountofmoney','standard', 'measure','create_at','update_at','contractnumber','year'], 'safe'],
            
        ];
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
//        var_dump(date('Y-m-d H:i:s',$params['begindate']));var_dump($params['begindate']);
//     	var_dump(date('Y-m-d H:i:s',$params['enddate']));var_dump($params['enddate']);
//    	var_dump($params);exit;
        $farmid=null;
        $query = Tempprintbill::find()->orderBy('id desc');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

//        if (!$this->validate()) {
//            // uncomment the following line if you do not want to any records when validation fails
//            // $query->where('0=1');
//            return $dataProvider;
//        }

        if(isset($params['begindate'])) {
            $year = date('Y',$params['begindate']);
        } else {
            $year = User::getYear();
        }
//		if(!($this->contract == null) or !($this->contract == '')) {
//	        if($this->contract > 0) {
//	            $query->andWhere('farms_id>0');
//	            $query->andFilterWhere(['year' => $year]);
//	        }else {
//	            $query->andFilterWhere(['farms_id'=>(int)$this->contract]);
//	        }
//		}
//        if($this->contract == '') {
//
//        }
        if(!empty($this->farmername) or !empty($this->farms_id) or !empty($this->contractnumber)) {
            $farm = Farms::find();
        }
        if(!empty($this->farmername)) {
            $farm->andFilterWhere($this->farmerpinyinSearch($this->farmername));
        }
        if(!empty($this->farms_id)) {
            $farm->andFilterWhere($this->pinyinSearch($this->farms_id));
        }
        if(!empty($this->contractnumber)) {
//         	$this->contractnumber = $params['collectionSearch']['contractnumber'];
        	$farm->andFilterWhere(['like','contractnumber',$this->contractnumber]);
        }
        if(isset($farm)) {
            foreach ($farm->all() as $value) {
                $farmid[] = $value['id'];
            }
        }
//        var_dump($this->state);exit;
        $query->andFilterWhere([
            'id' => $this->id,
        	'farms_id' => $farmid,
            'management_area' => $this->management_area,
            'standard' => $this->standard,
        	'state' => $this->state,
        	'year' => $this->year,
            'farmstate' => $this->farmstate,
//             'year' => User::getYear(),
        ]);
        if(isset($params['begindate']) and isset($params['enddate'])) {
            $query->andFilterWhere(['between','update_at',$params['begindate'],$params['enddate']]);
        }
        $query->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'bigamountofmoney', $this->bigamountofmoney])
        	->andFilterWhere(['like', 'measure', $this->measure])
        	->andFilterWhere(['like', 'amountofmoney', $this->amountofmoney])
        	->andFilterWhere(['like', 'nonumber', $this->nonumber]);
//         	->andFilterWhere(['like', 'contractnumber', $this->contractnumber])
//         	->andFilterWhere(['like', 'create_at', $this->create_at]);

// 		var_dump($dataProvider->query->where);exit;
        return $dataProvider;
    }

    public function searchIndex($params)
    {
//     	var_dump($params);
//     	var_dump($this->contract);
        $query = Tempprintbill::find()->orderBy('id DESC');
//     	$query->joinWith(['farms']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
//  		var_dump($params);exit;
        if(isset($params['tempprintbillSearch']['management_area'])) {
            if(count($params['tempprintbillSearch']['management_area']) > 1)
                $this->management_area = NULL;
            else
                $this->management_area = $params['tempprintbillSearch']['management_area'];
        } else {
            $management_area = Farms::getManagementArea()['id'];
			if(count($management_area) > 1)
				$this->management_area = NULL;
			else 
				$this->management_area = $management_area;
        }
//        var_dump($params['tempprintbillSearch']['state']);exit;
        if(isset($params['tempprintbillSearch']['state'])) {
            $this->state = $params['tempprintbillSearch']['state'];
        }
        if(isset($params['tempprintbillSearch']['contract'])) {
            $this->contract = $params['tempprintbillSearch']['contract'];
            switch($this->contract) {
                case 0:
                    $query->andFilterWhere(['farms_id'=>0]);
                    break;
                case -1:
                    $query->andFilterWhere(['farms_id'=>-1]);
                    break;
                default:
                    $query->andWhere('farms_id>0');
            }
        }
        if(isset($params['tempprintbillSearch']['farmstate'])) {
        	$this->farmstate = $params['tempprintbillSearch']['farmstate'];
    	}
        if(isset($params['tempprintbillSearch']['amountofmoney']) and $params['tempprintbillSearch']['amountofmoney'] !== '') {
            $query->andFilterWhere($this->numberSearch('amountofmoney',$params['tempprintbillSearch']['amountofmoney']));
        }
        if(isset($params['tempprintbillSearch']['measure']) and $params['tempprintbillSearch']['measure'] !== '') {
            $query->andFilterWhere($this->numberSearch('measure',$params['tempprintbillSearch']['measure']));
        }
        if(isset($params['tempprintbillSearch']['farmername']) and $params['tempprintbillSearch']['farmername'] !== '') {
            $this->farmername = $params['tempprintbillSearch']['farmername'];
        }
//        var_dump($this->farmstate);
        $query->andFilterWhere([
            'id' => $this->id,
            'state' => $this->state,
            'farmstate' => $this->farmstate,
//         	'contract' => $this->contract,
            'management_area' => $this->management_area,
        ]);

           $query->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])
               ->andFilterWhere(['like','farmername',$this->farmername]);
// 		var_dump($dataProvider->query->where[1]);exit;
        return $dataProvider;
    }
}
