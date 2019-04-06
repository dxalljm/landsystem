<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Machinescanning;

/**
 * MachinescanningSearch represents the model behind the search form about `app\models\Machinescanning`.
 */
class MachinescanningSearch extends Machinescanning
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'farms_id', 'create_at', 'update_at', 'pagenumber'], 'integer'],
            [['cardid', 'scanimage'], 'safe'],
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
        $query = Machinescanning::find();

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
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
            'pagenumber' => $this->pagenumber,
        ]);

        $query->andFilterWhere(['like', 'cardid', $this->cardid])
            ->andFilterWhere(['like', 'scanimage', $this->scanimage]);

        return $dataProvider;
    }
}
