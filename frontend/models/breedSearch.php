<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Breed;

/**
 * breedSearch represents the model behind the search form about `app\models\Breed`.
 */
class breedSearch extends Breed
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'farms_id', 'is_demonstration'], 'integer'],
            [['breedname', 'breedaddress'], 'safe'],
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
        $query = Breed::find();

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
            'is_demonstration' => $this->is_demonstration,
        ]);

        $query->andFilterWhere(['like', 'breedname', $this->breedname])
            ->andFilterWhere(['like', 'breedaddress', $this->breedaddress]);

        return $dataProvider;
    }
}
