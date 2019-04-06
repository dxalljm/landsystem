<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Insurancetype;

/**
 * InsurancetypeSearch represents the model behind the search form about `app\models\Insurancetype`.
 */
class InsurancetypeSearch extends Insurancetype
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'plant_id'], 'integer'],
            [['pinyin'], 'string']
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
        $query = Insurancetype::find();

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
            'plant_id' => $this->plant_id,
        ]);

        $query->andFilterWhere(['like','pinyin',$this->pinyin]);

        return $dataProvider;
    }
}
