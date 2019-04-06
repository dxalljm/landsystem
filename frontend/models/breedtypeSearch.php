<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Breedtype;

/**
 * breedtypeSearch represents the model behind the search form about `app\models\Breedtype`.
 */
class breedtypeSearch extends Breedtype
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'father_id'], 'integer'],
            [['typename','unit'], 'safe'],
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
        $query = Breedtype::find();

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
        ]);

        $query->andFilterWhere(['like', 'typename', $this->typename])
        	->andFilterWhere(['like', 'unit', $this->unit]);

        return $dataProvider;
    }
}
