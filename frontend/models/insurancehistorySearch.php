<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Insurancehistory;

/**
 * insurancehistorySearch represents the model behind the search form about `app\models\Insurancehistory`.
 */
class insurancehistorySearch extends Insurancehistory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'management_area', 'state', 'fwdtstate', 'issame', 'isselfselect', 'nameissame', 'isbxsame'], 'integer'],
            [['year', 'farmname', 'contractnumber', 'policyholder', 'cardid', 'telephone', 'company_id', 'create_at', 'update_at', 'policyholdertime', 'managemanttime', 'halltime', 'farmername', 'statecontent', 'farmerpinyin', 'policyholderpinyin'], 'safe'],
            [['wheat', 'soybean', 'insuredarea', 'insuredwheat', 'insuredsoybean', 'other', 'insuredother', 'contractarea'], 'number'],
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
        $query = Insurancehistory::find();

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
            'wheat' => $this->wheat,
            'soybean' => $this->soybean,
            'insuredarea' => $this->insuredarea,
            'insuredwheat' => $this->insuredwheat,
            'insuredsoybean' => $this->insuredsoybean,
            'other' => $this->other,
            'insuredother' => $this->insuredother,
            'state' => $this->state,
            'fwdtstate' => $this->fwdtstate,
            'issame' => $this->issame,
            'isselfselect' => $this->isselfselect,
            'nameissame' => $this->nameissame,
            'contractarea' => $this->contractarea,
            'isbxsame' => $this->isbxsame,
        ]);

        $query->andFilterWhere(['like', 'year', $this->year])
            ->andFilterWhere(['like', 'farmname', $this->farmname])
            ->andFilterWhere(['like', 'contractnumber', $this->contractnumber])
            ->andFilterWhere(['like', 'policyholder', $this->policyholder])
            ->andFilterWhere(['like', 'cardid', $this->cardid])
            ->andFilterWhere(['like', 'telephone', $this->telephone])
            ->andFilterWhere(['like', 'company_id', $this->company_id])
            ->andFilterWhere(['like', 'create_at', $this->create_at])
            ->andFilterWhere(['like', 'update_at', $this->update_at])
            ->andFilterWhere(['like', 'policyholdertime', $this->policyholdertime])
            ->andFilterWhere(['like', 'managemanttime', $this->managemanttime])
            ->andFilterWhere(['like', 'halltime', $this->halltime])
            ->andFilterWhere(['like', 'farmername', $this->farmername])
            ->andFilterWhere(['like', 'statecontent', $this->statecontent])
            ->andFilterWhere(['like', 'farmerpinyin', $this->farmerpinyin])
            ->andFilterWhere(['like', 'policyholderpinyin', $this->policyholderpinyin]);

        return $dataProvider;
    }
}
