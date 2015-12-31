<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Breedinfo;
use app\models\Theyear;
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
            [['id', 'breed_id', 'number', 'breedtype_id','create_at','update_at','management_area'], 'integer'],
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

        $query->andFilterWhere([
            'id' => $this->id,
            'breed_id' => $this->breed_id,
            'number' => $this->number,
            'basicinvestment' => $this->basicinvestment,
            'housingarea' => $this->housingarea,
            'breedtype_id' => $this->breedtype_id,
        	'management_area' => $this->management_area,
        ]);
//         $query->andFilterWhere(['between','update_at',Theyear::getYeartime()['begindate'],Theyear::getYeartime()['enddate']]);
        return $dataProvider;
    }
}
