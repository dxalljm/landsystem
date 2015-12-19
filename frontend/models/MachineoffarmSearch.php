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
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'machine_id', 'farms_id','create_at','update_at'], 'integer'],
        	[['machinename'],'string'],
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

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'machine_id' => $this->machine_id,
            'farms_id' => $this->farms_id,
        ]);
        $query->andFilterWhere(['like', 'machinename', $this->machinename])
        ->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]]);
        return $dataProvider;
    }
}
