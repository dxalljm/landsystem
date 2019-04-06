<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Machineoffarm;
use app\models\Theyear;

/**
 * MachineoffarmSearch represents the model behind the search form about `app\models\Machineoffarm`.
 */
class MachineoffarmSearch extends Machineoffarm
{
    public $bisclass;
    public $smallclass;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'machine_id', 'farms_id','create_at','update_at','machinetype_id','acquisitiontime','management_area','bigclass','smallclass'], 'integer'],
        	[['machinename','cardid'],'string'],
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
        $query = Machineoffarm::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if(is_array($this->management_area) and count($this->management_area) > 1) {
            $this->management_area = null;
        }
        if($this->cardid == 'X') {
            $this->cardid = '-1';
        }
//        if (!$this->validate()) {
//            // uncomment the following line if you do not want to any records when validation fails
//            // $query->where('0=1');
//            return $dataProvider;
//        }
        $query->andFilterWhere([
            'id' => $this->id,
            'machine_id' => $this->machine_id,
            'cardid' => $this->cardid,
        	'machinetype_id' => $this->machine_id,
        	'acquisitiontime' => $this->acquisitiontime,
            'management_area' => $this->management_area
//            'year' => $this->year,
        ]);
        $query->andFilterWhere(['like', 'machinename', $this->machinename]);
//         ->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]]);
        return $dataProvider;
    }
}
