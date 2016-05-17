<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Insurance;

/**
 * insuranceSearch represents the model behind the search form about `app\models\Insurance`.
 */
class insuranceSearch extends Insurance
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'management_area', 'farms_id'], 'integer'],
            [['year', 'policyholder', 'cardid', 'telephone', 'company_id', 'create_at', 'update_at', 'policyholdertime', 'managemanttime', 'halltime'], 'safe'],
            [['wheat', 'soybean', 'insuredarea', 'insuredwheat', 'insuredsoybean'], 'number'],
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
        $query = Insurance::find();

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
            'management_area' => $this->management_area,
            'farms_id' => $this->farms_id,
            'wheat' => $this->wheat,
            'soybean' => $this->soybean,
            'insuredarea' => $this->insuredarea,
            'insuredwheat' => $this->insuredwheat,
            'insuredsoybean' => $this->insuredsoybean,
        ]);

        $query->andFilterWhere(['like', 'year', $this->year])
            ->andFilterWhere(['like', 'policyholder', $this->policyholder])
            ->andFilterWhere(['like', 'cardid', $this->cardid])
            ->andFilterWhere(['like', 'telephone', $this->telephone])
            ->andFilterWhere(['like', 'company_id', $this->company_id])
            ->andFilterWhere(['like', 'create_at', $this->create_at])
            ->andFilterWhere(['like', 'update_at', $this->update_at])
            ->andFilterWhere(['like', 'policyholdertime', $this->policyholdertime])
            ->andFilterWhere(['like', 'managemanttime', $this->managemanttime])
            ->andFilterWhere(['like', 'halltime', $this->halltime]);

        return $dataProvider;
    }
}
