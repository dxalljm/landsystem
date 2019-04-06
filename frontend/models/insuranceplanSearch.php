<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Insuranceplan;

/**
 * InsuranceplanSearch represents the model behind the search form about `app\models\Insuranceplan`.
 */
class InsuranceplanSearch extends Insuranceplan
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'insurance_id', 'management_area', 'farms_id', 'create_at', 'update_at', 'policyholdertime', 'state', 'farmstate', 'lease_id'], 'integer'],
            [['year', 'policyholder', 'cardid', 'telephone', 'farmername', 'farmerpinyin', 'policyholderpinyin', 'insured'], 'safe'],
            [['insuredarea', 'contractarea'], 'number'],
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
        $query = Insuranceplan::find();

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
            'insurance_id' => $this->insurance_id,
            'management_area' => $this->management_area,
            'farms_id' => $this->farms_id,
            'insuredarea' => $this->insuredarea,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
            'policyholdertime' => $this->policyholdertime,
            'state' => $this->state,
            'contractarea' => $this->contractarea,
            'farmstate' => $this->farmstate,
            'lease_id' => $this->lease_id,
        ]);

        $query->andFilterWhere(['like', 'year', $this->year])
            ->andFilterWhere(['like', 'policyholder', $this->policyholder])
            ->andFilterWhere(['like', 'cardid', $this->cardid])
            ->andFilterWhere(['like', 'telephone', $this->telephone])
            ->andFilterWhere(['like', 'farmername', $this->farmername])
            ->andFilterWhere(['like', 'farmerpinyin', $this->farmerpinyin])
            ->andFilterWhere(['like', 'policyholderpinyin', $this->policyholderpinyin])
            ->andFilterWhere(['like', 'insured', $this->insured]);

        return $dataProvider;
    }
}
