<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "article_to_related_article".
 *
 * @property int $article_id
 * @property int $related_article_id
 *
 * @property Article $article
 * @property Article $relatedArticle
 */
class ArticleToRelatedArticle extends \common\db\MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_to_related_article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['article_id', 'related_article_id'], 'required'],
            [['article_id', 'related_article_id'], 'integer'],
            [['article_id', 'related_article_id'], 'unique', 'targetAttribute' => ['article_id', 'related_article_id']],
            [['article_id'], 'exist', 'skipOnError' => true, 'targetClass' => Article::className(), 'targetAttribute' => ['article_id' => 'id']],
            [['related_article_id'], 'exist', 'skipOnError' => true, 'targetClass' => Article::className(), 'targetAttribute' => ['related_article_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'article_id' => 'Article ID',
            'related_article_id' => 'Related Article ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticle()
    {
        return $this->hasOne(Article::className(), ['id' => 'article_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelatedArticle()
    {
        return $this->hasOne(Article::className(), ['id' => 'related_article_id']);
    }
}
