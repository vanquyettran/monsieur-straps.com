<?php
/**
 * Created by PhpStorm.
 * User: Quyet
 * Date: 1/4/2018
 * Time: 2:49 PM
 */

namespace backend\models;


use yii\helpers\ArrayHelper;

class Article extends \common\models\Article
{
    /**
     * @var $related_article_ids null|int[]
     * @var $tag_ids null|int[]
     */
    public $related_article_ids = null;
    public $tag_ids = null;

    public function saveArticleToRelatedArticles()
    {
        if (is_array($this->related_article_ids)) {
            $currentJunctions = $this->articleToRelatedArticles;
            foreach ($currentJunctions as $currentJunction) {
                if (!in_array($currentJunction->related_article_id, $this->related_article_ids)) {
                    if (!$currentJunction->delete()) {
                        $currentJunction->addDeleteFailureFlash();
                    }
                }
            }
            $current_related_article_ids = ArrayHelper::getColumn($currentJunctions, 'related_article_id');
            foreach ($this->related_article_ids as $article_id) {
                if (!in_array($article_id, $current_related_article_ids)) {
                    $junction = new ArticleToRelatedArticle();
                    $junction->article_id = $this->id;
                    $junction->related_article_id = $article_id;
                    if (!$junction->save()) {
                        $junction->addCreateFailureFlash();
                    }
                }
            }
        }
    }

    public function saveArticleToTags()
    {
        if (is_array($this->tag_ids)) {
            $currentJunctions = $this->articleToTags;
            foreach ($currentJunctions as $currentJunction) {
                if (!in_array($currentJunction->tag_id, $this->tag_ids)) {
                    if (!$currentJunction->delete()) {
                        $currentJunction->addDeleteFailureFlash();
                    }
                }
            }
            $current_tag_ids = ArrayHelper::getColumn($currentJunctions, 'tag_id');
            foreach ($this->tag_ids as $article_id) {
                if (!in_array($article_id, $current_tag_ids)) {
                    $junction = new ArticleToTag();
                    $junction->article_id = $this->id;
                    $junction->tag_id = $article_id;
                    if (!$junction->save()) {
                        $junction->addCreateFailureFlash();
                    }
                }
            }
        }
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['related_article_ids', 'tag_ids'], 'each', 'rule' => ['integer']],
            ['related_article_ids', 'avoidRelateToSelf']
        ]);
    }

    public function avoidRelateToSelf($attribute, $params, $validator)
    {
        if (in_array($this->id, $this->related_article_ids)) {
            $this->addError('related_article_ids', 'Article cannot relate to itself.');
        }
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'related_article_ids' => 'Related Article IDs',
            'tag_ids' => 'Tag IDs',
        ]);
    }

}