<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Machinetype;

/**
 * MachinetypeSearch represents the model behind the search form about `app\models\Machinetype`.
 */
class MachinetypeSearch extends Machinetype
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'father_id', 'is_delete', 'sort'], 'integer'],
            [['typename'], 'safe'],
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
        $query = Machinetype::find();

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
            'father_id' => $this->father_id,
            'is_delete' => $this->is_delete,
            'sort' => $this->sort,
        ]);

        $query->andFilterWhere(['like', 'typename', $this->typename]);

        return $dataProvider;
    }
}
