<?php

use yii\db\Migration;

/**
 * Handles adding banner_caption_column_banner_image_id to table `article_category`.
 * Has foreign keys to the tables:
 *
 * - `image`
 */
class m181118_115129_add_banner_caption_column_banner_image_id_column_to_article_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('article_category', 'banner_caption', $this->string(2047));
        $this->addColumn('article_category', 'banner_image_id', $this->integer());

        // creates index for column `banner_image_id`
        $this->createIndex(
            'idx-article_category-banner_image_id',
            'article_category',
            'banner_image_id'
        );

        // add foreign key for table `image`
        $this->addForeignKey(
            'fk-article_category-banner_image_id',
            'article_category',
            'banner_image_id',
            'image',
            'id',
            'RESTRICT'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `image`
        $this->dropForeignKey(
            'fk-article_category-banner_image_id',
            'article_category'
        );

        // drops index for column `banner_image_id`
        $this->dropIndex(
            'idx-article_category-banner_image_id',
            'article_category'
        );

        $this->dropColumn('article_category', 'banner_caption');
        $this->dropColumn('article_category', 'banner_image_id');
    }
}
