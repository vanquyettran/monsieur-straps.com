<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Product;

/**
 * ProductSearch represents the model behind the search form of `backend\models\Product`.
 */
class ProductSearch extends Product
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'price', 'active', 'visible', 'featured', 'allow_indexing', 'allow_following', 'production_status', 'view_count', 'creator_id', 'updater_id', 'avatar_image_id', 'product_category_id'], 'integer'],
            [['name', 'slug', 'heading', 'page_title', 'meta_title', 'meta_keywords', 'meta_description', 'description', 'code', 'long_description', 'details', 'published_time', 'created_time', 'updated_time'], 'safe'],
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
        $query = Product::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'price' => $this->price,
            'active' => $this->active,
            'visible' => $this->visible,
            'featured' => $this->featured,
            'allow_indexing' => $this->allow_indexing,
            'allow_following' => $this->allow_following,
            'production_status' => $this->production_status,
            'published_time' => $this->published_time,
            'created_time' => $this->created_time,
            'updated_time' => $this->updated_time,
            'view_count' => $this->view_count,
            'creator_id' => $this->creator_id,
            'updater_id' => $this->updater_id,
            'avatar_image_id' => $this->avatar_image_id,
            'product_category_id' => $this->product_category_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'heading', $this->heading])
            ->andFilterWhere(['like', 'page_title', $this->page_title])
            ->andFilterWhere(['like', 'meta_title', $this->meta_title])
            ->andFilterWhere(['like', 'meta_keywords', $this->meta_keywords])
            ->andFilterWhere(['like', 'meta_description', $this->meta_description])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'long_description', $this->long_description])
            ->andFilterWhere(['like', 'details', $this->details]);

        return $dataProvider;
    }
}
