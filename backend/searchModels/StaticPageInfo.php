<?php

namespace backend\searchModels;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\StaticPageInfo as StaticPageInfoModel;

/**
 * StaticPageInfo represents the model behind the search form of `backend\models\StaticPageInfo`.
 */
class StaticPageInfo extends StaticPageInfoModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'active', 'allow_indexing', 'allow_following', 'avatar_image_id'], 'integer'],
            [['name', 'route', 'url_pattern', 'heading', 'page_title', 'meta_title', 'meta_keywords', 'meta_description', 'long_description'], 'safe'],
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
        $query = StaticPageInfoModel::find();

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
            'active' => $this->active,
            'allow_indexing' => $this->allow_indexing,
            'allow_following' => $this->allow_following,
            'avatar_image_id' => $this->avatar_image_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'route', $this->route])
            ->andFilterWhere(['like', 'url_pattern', $this->url_pattern])
            ->andFilterWhere(['like', 'heading', $this->heading])
            ->andFilterWhere(['like', 'page_title', $this->page_title])
            ->andFilterWhere(['like', 'meta_title', $this->meta_title])
            ->andFilterWhere(['like', 'meta_keywords', $this->meta_keywords])
            ->andFilterWhere(['like', 'meta_description', $this->meta_description])
            ->andFilterWhere(['like', 'long_description', $this->long_description]);

        return $dataProvider;
    }
}
