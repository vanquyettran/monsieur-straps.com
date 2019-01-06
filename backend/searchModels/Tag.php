<?php

namespace backend\searchModels;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Tag as TagModel;

/**
 * Tag represents the model behind the search form of `backend\models\Tag`.
 */
class Tag extends TagModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'active', 'visible', 'featured', 'allow_indexing', 'allow_following', 'sort_order', 'creator_id', 'updater_id', 'avatar_image_id'], 'integer'],
            [['name', 'slug', 'heading', 'page_title', 'meta_title', 'meta_keywords', 'meta_description', 'long_description', 'created_time', 'updated_time'], 'safe'],
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
        $query = TagModel::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder' => [
                    'sort_order' => SORT_ASC,
                ]
            ],
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
            'visible' => $this->visible,
            'featured' => $this->featured,
            'allow_indexing' => $this->allow_indexing,
            'allow_following' => $this->allow_following,
            'sort_order' => $this->sort_order,
            'created_time' => $this->created_time,
            'updated_time' => $this->updated_time,
            'creator_id' => $this->creator_id,
            'updater_id' => $this->updater_id,
            'avatar_image_id' => $this->avatar_image_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'heading', $this->heading])
            ->andFilterWhere(['like', 'page_title', $this->page_title])
            ->andFilterWhere(['like', 'meta_title', $this->meta_title])
            ->andFilterWhere(['like', 'meta_keywords', $this->meta_keywords])
            ->andFilterWhere(['like', 'meta_description', $this->meta_description])
            ->andFilterWhere(['like', 'long_description', $this->long_description]);

        return $dataProvider;
    }
}
