<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Processname;

/**
 * ProcessnameSearch represents the model behind the search form about `app\models\Processname`.
 */
class ProcessnameSearch extends Processname
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['processdepartment', 'Identification','department_id','level_id'], 'safe'],
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
        $query = Processname::find();

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
        ]);

        $query->andFilterWhere(['like', 'processdepartment', $this->processdepartment])
            ->andFilterWhere(['like', 'Identification', $this->Identification])
            ->andFilterWhere(['like', 'department_id', $this->department_id])
	        ->andFilterWhere(['like', 'level_id', $this->level_id]);

        return $dataProvider;
    }
}
