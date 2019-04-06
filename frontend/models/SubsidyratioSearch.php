<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Subsidyratio;

/**
 * SubsidyratioSearch represents the model behind the search form about `app\models\Subsidyratio`.
 */
class SubsidyratioSearch extends Subsidyratio
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'farms_id', 'typeid'], 'integer'],
            [['farmer', 'lessee'], 'number'],
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
        $query = Subsidyratio::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'farms_id' => $this->farms_id,
            'typeid' => $this->typeid,
            'farmer' => $this->farmer,
            'lessee' => $this->lessee,
        ]);

        return $dataProvider;
    }
}
