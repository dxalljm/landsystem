<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\BankAccount;

/**
 * bankaccountSearch represents the model behind the search form about `app\models\BankAccount`.
 */
class bankaccountSearch extends BankAccount
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['id','farms_id','state'], 'integer'],
//            [['accountnumber', 'bank','cardid','lessee'], 'safe'],

            [['id','farms_id','state','modfiytime','farmstate','management_area'],'integer'],
            [['accountnumber', 'bank','cardid','lessee','modfiyname','farmerpinyin','lesseepinyin','farmername','contractnumber','farmpinyin','farmname'], 'safe'],
            [['contractarea'],'number'],
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
        if (!empty($this->contractarea)) {
            preg_match_all('/(.*)([0-9]+?)/iU', $this->contractarea, $where);
            //print_r($where);

            // 		string(2) ">="
            // 		string(3) "300"
            if ($where[1][0] == '>' or $where[1][0] == '>=')
                $tj = ['between', 'contractarea', (float)$where[2][0], (float)99999.0];
            if ($where[1][0] == '<' or $where[1][0] == '<=')
                $tj = ['between', 'contractarea', (float)0.0, (float)$where[2][0]];
            if ($where[1][0] == '')
                $tj = ['like', 'contractarea', $this->contractarea];
        } else
            $tj = ['like', 'contractarea', $this->contractarea];
        //var_dump($tj);
        return $tj;
    }

    public function measureSearch($str = NULL)
    {
        $this->measure = $str;
        if (!empty($this->measure)) {
            preg_match_all('/(.*)([0-9]+?)/iU', $this->measure, $where);
            //print_r($where);

            // 		string(2) ">="
            // 		string(3) "300"
            if ($where[1][0] == '>' or $where[1][0] == '>=')
                $tj = ['between', 'measure', (float)$where[2][0], (float)99999.0];
            if ($where[1][0] == '<' or $where[1][0] == '<=')
                $tj = ['between', 'measure', (float)0.0, (float)$where[2][0]];
            if ($where[1][0] == '')
                $tj = ['like', 'measure', $this->measure];
        } else
            $tj = ['like', 'measure', $this->measure];
        //var_dump($tj);
        return $tj;
    }

    public function contractareaSearch($str = NULL)
    {
        $this->contractarea = $str;
        if (!empty($this->contractarea)) {
            preg_match_all('/(.*)([0-9]+?)/iU', $this->contractarea, $where);
            //print_r($where);

            // 		string(2) ">="
            // 		string(3) "300"
            if ($where[1][0] == '>' or $where[1][0] == '>=')
                $tj = ['between', 'contractarea', (float)$where[2][0], (float)99999.0];
            if ($where[1][0] == '<' or $where[1][0] == '<=')
                $tj = ['between', 'contractarea', (float)0.0, (float)$where[2][0]];
            if ($where[1][0] == '')
                $tj = ['like', 'contractarea', $this->contractarea];
        } else
            $tj = ['like', 'contractarea', $this->contractarea];
        //var_dump($tj);
        return $tj;
    }

    public function pinyinSearch($str = NULL)
    {

        $this->farmname = $str;
        if (preg_match("/^[A-Za-z]/", $this->farmname)) {
            $tj = ['like', 'farmpinyin', $this->farmname];
        } else {
            $tj = ['like', 'farmname', $this->farmname];
        }
//     	var_dump($tj);exit;
        return $tj;
    }

    public function farmerpinyinSearch($str = NULL)
    {
//     	var_dump($str);exit;
        $this->farmername = $str;
        if (preg_match("/^[A-Za-z]/", $this->farmername)) {
            $tj = ['like', 'farmerpinyin', $this->farmername];
        } else {
            $tj = ['like', 'farmername', $this->farmername];
        }
//     	var_dump($tj);exit;
        return $tj;
    }
    public function lesseepinyinSearch($str = NULL)
    {

        $this->lessee = $str;
        if (preg_match("/^[A-Za-z]/", $this->lessee)) {
            $tj = ['like', 'lesseepinyin', $this->lessee];
        } else {
            $tj = ['like', 'lessee', $this->lessee];
        }
//     	var_dump($tj);exit;
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
//        var_dump($params)
        $query = BankAccount::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if($this->state == '') {
            $this->state = null;
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'farms_id' => $this->farms_id,
            'state' => $this->state,
            'farmstate' => $this->farmstate,
            'management_area' => $this->management_area,
        ]);

        $query->andFilterWhere(['like', 'accountnumber', $this->accountnumber])
            ->andFilterWhere(['like', 'bank', $this->bank])
//            ->andFilterWhere(['like','lessee',$this->lessee])
            ->andFilterWhere($this->lesseepinyinSearch($this->lessee))
            ->andFilterWhere($this->pinyinSearch($this->farmname))
            ->andFilterWhere($this->farmerpinyinSearch($this->farmername))
            ->andFilterWhere(['like', 'accountnumber', $this->accountnumber])
            ->andFilterWhere($this->contractareaSearch($this->contractarea))
            ->andFilterWhere($this->betweenSearch())
            ->andFilterWhere(['like','cardid',$this->cardid]);
//        var_dump($dataProvider->query->where);exit;
        return $dataProvider;
    }
}
