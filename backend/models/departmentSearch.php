<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Department;

/**
 * departmentSearch represents the model behind the search form about `app\models\Department`.
 */
class departmentSearch extends Department
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['departmentname', 'membership','leader','sectionchief','chippackage'], 'safe'],
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
        $query = Department::find();

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

        $query->andFilterWhere(['like', 'departmentname', $this->departmentname])
            ->andFilterWhere(['like', 'membership', $this->membership])
            ->andFilterWhere(['like', 'leader', $this->leader])
            ->andFilterWhere(['like', 'sectionchief', $this->sectionchief])
            ->andFilterWhere(['like', 'chippackage', $this->chippackage]);

        return $dataProvider;
    }
}
