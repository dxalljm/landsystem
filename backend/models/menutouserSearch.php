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
            [['id', 'user_id',], 'integer'],
            [['menulist','username'], 'safe'],
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
        $query->joinWith(['user']);
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
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'menulist', $this->menulist])
        ->andFilterWhere(['like', 'land_user.username', $this->username]);

        return $dataProvider;
    }
}
