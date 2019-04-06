<?php

namespace frontend\models;

use app\models\Machine;
use app\models\Machineoffarm;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Machineapply;
use app\models\Farms;
/**
 * MachineapplySearch represents the model behind the search form about `app\models\Machineapply`.
 */
class MachineapplySearch extends Machineapply
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'farms_id', 'age', 'management_area', 'machineoffarm_id','state','dckstate','year','scanfinished','machinetype_id'], 'integer'],
            [['farmername', 'sex', 'domicile', 'cardid', 'telephone', 'farmerpinyin','subsidymoney'], 'safe'],
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
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */

    public function machieSearch($str)
    {
        if(empty($str)) {
            return null;
        }
        $result = [];
        $ms = Machine::find()->andFilterWhere(['like','implementmodel',$str])->all();
        foreach($ms as $m) {
            $mf = Machineoffarm::find()->where(['machine_id' => $m['id']])->all();
            foreach($mf as $f) {
                $result[] = $f['id'];
            }
        }
        return $result;
    }

    public function search($params)
    {
//        var_dump($params);exit;
        $query = Machineapply::find()->orderBy('id DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if(is_array($this->management_area) and count($this->management_area) > 1) {
            $this->management_area = null;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'farms_id' => $this->farms_id,
            'age' => $this->age,
            'management_area' => $this->management_area,
            'machineoffarm_id' => $this->machieSearch($this->machineoffarm_id),
            'state' => $this->state,
            'dckstate' => $this->dckstate,
            'year' => $this->year,
            'scanfinished' => $this->scanfinished,
            'machinetype_id' => $this->machinetype_id,
        ]);

        $query->andFilterWhere($this->farmerpinyinSearch($this->farmername))
            ->andFilterWhere(['like', 'sex', $this->sex])
            ->andFilterWhere(['like', 'domicile', $this->domicile])
            ->andFilterWhere(['like', 'cardid', $this->cardid])
            ->andFilterWhere(['like', 'telephone', $this->telephone])
            ->andFilterWhere(['like', 'subsidymoney', $this->subsidymoney])
            ->andFilterWhere(['like', 'farmerpinyin', $this->farmerpinyin]);
//        var_dump($dataProvider->query->where);exit;
        return $dataProvider;
    }

    public function searchIndex($params)
    {
//        var_dump($params);exit;
        $query = Machineapply::find()->orderBy('id DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

//        if (!$this->validate()) {
//            // uncomment the following line if you do not want to any records when validation fails
//            // $query->where('0=1');
//            return $dataProvider;
//        }
        if(isset($params['MachineapplySearch']['management_area'])) {
            if($params['MachineapplySearch']['management_area'] == 0)
                $this->management_area = NULL;
            else
                $this->management_area = $params['MachineapplySearch']['management_area'];
        } else {
            $management_area = Farms::getManagementArea()['id'];
            if(count($management_area) > 1)
                $this->management_area = NULL;
            else
                $this->management_area = $management_area;
        }

        

        $query->andFilterWhere([
            'id' => $this->id,
            'farms_id' => $this->farms_id,
            'age' => $this->age,
            'management_area' => $this->management_area,
            'machineoffarm_id' => $this->machineoffarm_id,
            'state' => $this->state,
            'dckstate' => $this->dckstate,
            'year' => $this->year,
            'scanfinished' => $this->scanfinished,
            'machinetype_id' => $this->machinetype_id,
        ]);

        $query->andFilterWhere($this->farmerpinyinSearch($this->farmername))
            ->andFilterWhere(['like', 'sex', $this->sex])
            ->andFilterWhere(['like', 'domicile', $this->domicile])
            ->andFilterWhere(['like', 'cardid', $this->cardid])
            ->andFilterWhere(['like', 'telephone', $this->telephone])
            ->andFilterWhere(['like', 'subsidymoney', $this->subsidymoney])
            ->andFilterWhere(['like', 'farmerpinyin', $this->farmerpinyin]);
//        $query->andFilterWhere(['between','update_at',$params['begindate'],$params['enddate']]);
        return $dataProvider;
    }

    public function searchSearch($params)
    {
//        var_dump($params);exit;
        $query = Machineapply::find()->orderBy('id DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

//        if (!$this->validate()) {
//            // uncomment the following line if you do not want to any records when validation fails
//            // $query->where('0=1');
//            return $dataProvider;
//        }
        if(isset($params['MachineapplySearch']['management_area'])) {
                $this->management_area = $params['MachineapplySearch']['management_area'];
        } else {
            $management_area = Farms::getManagementArea()['id'];
            if(count($management_area) > 1)
                $this->management_area = NULL;
            else
                $this->management_area = $management_area;
        }



        $query->andFilterWhere([
            'id' => $this->id,
            'farms_id' => $this->farms_id,
            'age' => $this->age,
            'management_area' => $this->management_area,
            'machineoffarm_id' => $this->machineoffarm_id,
            'state' => $this->state,
            'dckstate' => $this->dckstate,
            'year' => $this->year,
            'scanfinished' => $this->scanfinished,
            'machinetype_id' => $this->machinetype_id,
        ]);

        $query->andFilterWhere($this->farmerpinyinSearch($this->farmername))
            ->andFilterWhere(['like', 'sex', $this->sex])
            ->andFilterWhere(['like', 'domicile', $this->domicile])
            ->andFilterWhere(['like', 'cardid', $this->cardid])
            ->andFilterWhere(['like', 'telephone', $this->telephone])
            ->andFilterWhere(['like', 'subsidymoney', $this->subsidymoney])
            ->andFilterWhere(['like', 'farmerpinyin', $this->farmerpinyin]);
        $query->andFilterWhere(['between','update_at',$params['begindate'],$params['enddate']]);
        return $dataProvider;
    }
}
