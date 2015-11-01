<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Breedinfo;

/**
 * breedinfoSearch represents the model behind the search form about `app\models\Breedinfo`.
 */
class breedinfoSearch extends Breedinfo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'breed_id', 'number', 'breedtype_id'], 'integer'],
            [['basicinvestment', 'housingarea'], 'number'],
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
    	
        $query = Breedinfo::find();

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
            'breed_id' => $this->breed_id,
            'number' => $this->number,
            'basicinvestment' => $this->basicinvestment,
            'housingarea' => $this->housingarea,
            'breedtype_id' => $this->breedtype_id,
        ]);

        return $dataProvider;
    }
}
