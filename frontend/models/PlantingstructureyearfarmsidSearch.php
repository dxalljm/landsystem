<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Plantingstructureyearfarmsid;
use yii\helpers\ArrayHelper;
use app\models\Plantingstructurecheck;
use app\models\User;
use app\models\Farms;
/**
 * PlantingstructureyearfarmsidSearch represents the model behind the search form about `app\models\Plantingstructureyearfarmsid`.
 */
class PlantingstructureyearfarmsidSearch extends Plantingstructureyearfarmsid
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'farms_id', 'state', 'isfinished', 'create_at', 'management_area'], 'integer'],
            [['contractarea'], 'number'],
            [['contractnumber', 'year', 'farmname', 'farmername','cardid','telephone'], 'safe'],
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


    public function areaSearch($str = NULL)
    {
        if(!empty($str)) {
            preg_match_all('/(.*)([0-9]+?)/iU', $str, $where);
            //print_r($where);

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
        //var_dump($tj);
        return $tj;
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
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
//        var_dump($params);exit;
//        var_dump($_GET);exit;
        $query = Plantingstructureyearfarmsid::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

//        if (!$this->validate()) {
//            // uncomment the following line if you do not want to return any records when validation fails
//            // $query->where('0=1');
//            return $dataProvider;
//        }

        // grid filtering conditions
//        if(isset($params['PlantingstructureyearfarmsidSearch']['management_area'])) {
//            if($params['PlantingstructureyearfarmsidSearch']['management_area'] == 0)
//                $this->management_area = NULL;
//            else
//                $this->management_area = $params['PlantingstructureyearfarmsidSearch']['management_area'];
//        } else {
//            $management_area = Farms::getManagementArea()['id'];
//
//            if(count($management_area) > 1)
//                $this->management_area = NULL;
//            else
//                $this->management_area = $management_area;
//        }
        if(isset($_GET['PlantingstructureyearfarmsidSearch']['management_area'])) {
            $this->management_area = $_GET['PlantingstructureyearfarmsidSearch']['management_area'];
        }
//        var_dump($this->management_area);exit;
        if(count($this->management_area) > 1) {
            $this->management_area = NULL;
        }
//        var_dump($this->management_area);exit;
        $query->andFilterWhere([
            'id' => $this->id,
            'farms_id' => $this->farms_id,
            'state' => $this->state,
            'isfinished' => $this->isfinished,
            'create_at' => $this->create_at,
            'management_area' => $this->management_area,
        ]);

        $query->andFilterWhere(['like', 'year', $this->year])
//            ->andFilterWhere(['like', 'farmname', $this->farmname])
//            ->andFilterWhere(['like', 'farmername', $this->farmername])
            ->andFilterWhere($this->areaSearch($this->contractarea))
            ->andFilterWhere($this->pinyinSearch($this->farmname))
            ->andFilterWhere($this->farmerpinyinSearch($this->farmername));

//        $farms = ArrayHelper::map(self::find()->where($query->where)->all(),'id','contractarea');

//        if($this->isfinished === '1') {
////			var_dump($this->plantIsFinished);
//            $allid = [];
//            foreach ($farms as $farms_id => $contractarea) {
//                $plantsum = sprintf('%.2f',Plantingstructurecheck::find()->where(['year' => User::getYear(), 'farms_id' => $farms_id])->sum('area'));
////				var_dump($plantsum.'==='.$contractarea);
//                if(bccomp($plantsum,$contractarea) == 0) {
//                    $allid[] = $farms_id;
//                }
//            }
//            $query->andFilterWhere(['id' => $allid]);
////			var_dump($query->where);
////			exit;
//        }
//        if($this->isfinished === '0') {
////			var_dump($this->plantIsFinished);
//            $allid = [];
//            foreach ($farms as $farms_id => $contractarea) {
//                $plantsum = sprintf('%.2f',Plantingstructurecheck::find()->where(['year' => User::getYear(), 'farms_id' => $farms_id])->sum('area'));
//                if(bccomp($plantsum,$contractarea) == -1) {
//                    $allid[] = $farms_id;
//                }
//            }
//            if($allid) {
//                $query->andFilterWhere(['id' => $allid]);
//            } else {
//                $query->andFilterWhere(['id' => '0']);
//            }
////			var_dump($query->where);
////			exit;
//        }
//        var_dump($dataProvider->query->where);exit;
        return $dataProvider;
    }
}
