<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\tablefields;

/**
 * tablefieldsSearch represents the model behind the search form about `app\models\tablefields`.
 */
class tablefieldsSearch extends tablefields
{
	public $tablename;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['fields', 'type', 'tablename','cfields','tables_id'], 'safe'],
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
        $query = tablefields::find();
        $query->joinWith(['tables']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        //$query->joinWith('{{%tables}}');
        $query->andFilterWhere([
            'id' => $this->id,
            'tables_id' => $this->tables_id,
        ]);

        $query->andFilterWhere(['like', 'fields', $this->fields])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'cfields', $this->cfields])
        	->andFilterWhere(['like', 'land_tables.tablename', $this->tablename]);
        return $dataProvider;
    }
}
