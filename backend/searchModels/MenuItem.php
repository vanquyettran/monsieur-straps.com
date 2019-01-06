<?php

namespace backend\searchModels;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\MenuItem as MenuItemModel;

/**
 * MenuItem represents the model behind the search form of `backend\models\MenuItem`.
 */
class MenuItem extends MenuItemModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'menu_id', 'sort_order', 'article_category_id', 'article_id', 'parent_id'], 'integer'],
            [['label', 'anchor_target', 'anchor_rel', 'link', 'static_page_route'], 'safe'],
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
        $query = MenuItemModel::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder' => [
                    'menu_id' => SORT_ASC,
                    'parent_id' => SORT_ASC,
                    'sort_order' => SORT_ASC,
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'menu_id' => $this->menu_id,
            'sort_order' => $this->sort_order,
            'article_category_id' => $this->article_category_id,
            'article_id' => $this->article_id,
            'parent_id' => $this->parent_id,
        ]);

        $query->andFilterWhere(['like', 'label', $this->label])
            ->andFilterWhere(['like', 'anchor_target', $this->anchor_target])
            ->andFilterWhere(['like', 'anchor_rel', $this->anchor_rel])
            ->andFilterWhere(['like', 'link', $this->link])
            ->andFilterWhere(['like', 'static_page_route', $this->static_page_route]);

        return $dataProvider;
    }
}
