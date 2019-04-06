<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MenuToUser;

/**
 * menutouserSearch represents the model behind the search form about `app\models\MenuToUser`.
 */
class menutouserSearch extends MenuToUser
{
	public $username;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id',], 'integer'],
            [['role_id','menulist','username','plate','businessmenu'], 'safe'],
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
        $query = MenuToUser::find();
//         $query->joinWith(['user']);
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
        ]);

        $query->andFilterWhere(['like', 'menulist', $this->menulist])
        ->andFilterWhere(['like', 'role_id', $this->role_id])
        ->andFilterWhere(['like', 'plate', $this->plate])
        ->andFilterWhere(['like', 'businessmenu', $this->businessmenu]);

        return $dataProvider;
    }
}
