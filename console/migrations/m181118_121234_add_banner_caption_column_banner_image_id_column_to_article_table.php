<?php

use yii\db\Migration;

/**
 * Handles adding banner_caption_column_banner_image_id to table `article`.
 * Has foreign keys to the tables:
 *
 * - `image`
 */
class m181118_121234_add_banner_caption_column_banner_image_id_column_to_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('article', 'banner_caption', $this->string(2047));
        $this->addColumn('article', 'banner_image_id', $this->integer());

        // creates index for column `banner_image_id`
        $this->createIndex(
            'idx-article-banner_image_id',
            'article',
            'banner_image_id'
        );

        // add foreign key for table `image`
        $this->addForeignKey(
            'fk-article-banner_image_id',
            'article',
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
            'fk-article-banner_image_id',
            'article'
        );

        // drops index for column `banner_image_id`
        $this->dropIndex(
            'idx-article-banner_image_id',
            'article'
        );

        $this->dropColumn('article', 'banner_caption');
        $this->dropColumn('article', 'banner_image_id');
    }
}
