<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Otherfarms;

/**
 * otherfarmsSearch represents the model behind the search form about `app\models\Otherfarms`.
 */
class otherfarmsSearch extends Otherfarms
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'farms_id'], 'integer'],
            [['measure'], 'number'],
            [['describe', 'contractnumber', 'zongdi', 'remarks'], 'safe'],
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
        $query = Otherfarms::find();

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
            'measure' => $this->measure,
        ]);

        $query->andFilterWhere(['like', 'describe', $this->describe])
            ->andFilterWhere(['like', 'contractnumber', $this->contractnumber])
            ->andFilterWhere(['like', 'zongdi', $this->zongdi])
            ->andFilterWhere(['like', 'remarks', $this->remarks]);

        return $dataProvider;
    }
}
