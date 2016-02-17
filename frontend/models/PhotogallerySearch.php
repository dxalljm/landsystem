<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Photogallery;

/**
 * PhotogallerySearch represents the model behind the search form about `app\models\Photogallery`.
 */
class PhotogallerySearch extends Photogallery
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'management_area', 'farms_id'], 'integer'],
            [['tablename', 'picaddress'], 'safe'],
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
        $query = Photogallery::find();

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
        ]);

        $query->andFilterWhere(['like', 'tablename', $this->tablename])
            ->andFilterWhere(['like', 'picaddress', $this->picaddress]);

        return $dataProvider;
    }
}
