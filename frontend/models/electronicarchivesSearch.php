<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Electronicarchives;

/**
 * electronicarchivesSearch represents the model behind the search form about `app\models\Electronicarchives`.
 */
class electronicarchivesSearch extends Electronicarchives
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'farms_id', 'pagenumber'], 'integer'],
            [['archivesimage', 'create_at', 'update_at'], 'safe'],
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
        $query = Electronicarchives::find();

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
            'pagenumber' => $this->pagenumber,
        ]);

        $query->andFilterWhere(['like', 'archivesimage', $this->archivesimage])
            ->andFilterWhere(['like', 'create_at', $this->create_at])
            ->andFilterWhere(['like', 'update_at', $this->update_at]);

        return $dataProvider;
    }
}
