<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Parcel;

/**
 * parcelSearch represents the model behind the search form about `app\models\Parcel`.
 */
class parcelSearch extends Parcel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'serialnumber', 'temporarynumber','farms_id'], 'integer'],
            [['unifiedserialnumber', 'powei', 'poxiang', 'podu', 'agrotype', 'stonecontent', 'figurenumber'], 'safe'],
            [['grossarea', 'piecemealarea', 'netarea'], 'number'],
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
        $query = Parcel::find();

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
            'serialnumber' => $this->serialnumber,
            'temporarynumber' => $this->temporarynumber,
            'grossarea' => $this->grossarea,
            'piecemealarea' => $this->piecemealarea,
            'netarea' => $this->netarea,
        ]);

        $query->andFilterWhere(['like', 'unifiedserialnumber', $this->unifiedserialnumber])
            ->andFilterWhere(['like', 'powei', $this->powei])
            ->andFilterWhere(['like', 'poxiang', $this->poxiang])
            ->andFilterWhere(['like', 'podu', $this->podu])
            ->andFilterWhere(['like', 'agrotype', $this->agrotype])
            ->andFilterWhere(['like', 'stonecontent', $this->stonecontent])
            ->andFilterWhere(['like', 'figurenumber', $this->figurenumber]);

        return $dataProvider;
    }
}
