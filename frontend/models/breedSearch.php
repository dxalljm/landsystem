<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Breed;
use app\models\Theyear;
/**
 * breedSearch represents the model behind the search form about `app\models\Breed`.
 */
class breedSearch extends Breed
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'farms_id', 'is_demonstration','create_at','update_at','management_area'], 'integer'],
            [['breedname', 'breedaddress'], 'safe'],
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
        $query = Breed::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $query->andFilterWhere([
            'id' => $this->id,
            'farms_id' => $this->farms_id,
            'is_demonstration' => $this->is_demonstration,
        	'create_at' => $this->create_at,
        	'upcate_at' => $this->update_at,
        	'management_area' => $this->management_area,
        ]);

        $query->andFilterWhere(['like', 'breedname', $this->breedname])
            ->andFilterWhere(['like', 'breedaddress', $this->breedaddress])
            ->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]]);
        return $dataProvider;
    }
}
