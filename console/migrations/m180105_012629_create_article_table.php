<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article`.
 * Has foreign keys to the tables:
 *
 * - `user`
 * - `user`
 * - `image`
 * - `article_category`
 * - `game`
 */
class m180105_012629_create_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('article', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull()->unique(),
            'heading' => $this->string(),
            'page_title' => $this->string(),
            'meta_title' => $this->string(),
            'meta_keywords' => $this->string(511),
            'meta_description' => $this->string(511),
            'menu_item_label' => $this->string(),
            'description' => $this->string(511)->notNull(),
            'content' => $this->text()->notNull(),
            'active' => $this->smallInteger()->notNull()->defaultValue(0),
            'visible' => $this->smallInteger()->notNull()->defaultValue(0),
            'featured' => $this->smallInteger()->notNull()->defaultValue(0),
            'allow_indexing' => $this->smallInteger()->notNull()->defaultValue(0),
            'allow_following' => $this->smallInteger()->notNull()->defaultValue(0),
            'view_count' => $this->integer()->notNull()->defaultValue(0),
            'published_time' => $this->bigInteger()->notNull(),
            'created_time' => $this->bigInteger()->notNull(),
            'updated_time' => $this->bigInteger()->notNull(),
            'creator_id' => $this->integer()->notNull(),
            'updater_id' => $this->integer()->notNull(),
            'avatar_image_id' => $this->integer()->notNull(),
            'article_category_id' => $this->integer()->notNull(),
        ], $tableOptions);

        // creates index for column `creator_id`
        $this->createIndex(
            'idx-article-creator_id',
            'article',
            'creator_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-article-creator_id',
            'article',
            'creator_id',
            'user',
            'id',
            'RESTRICT'
        );

        // creates index for column `updater_id`
        $this->createIndex(
            'idx-article-updater_id',
            'article',
            'updater_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-article-updater_id',
            'article',
            'updater_id',
            'user',
            'id',
            'RESTRICT'
        );

        // creates index for column `avatar_image_id`
        $this->createIndex(
            'idx-article-avatar_image_id',
            'article',
            'avatar_image_id'
        );

        // add foreign key for table `image`
        $this->addForeignKey(
            'fk-article-avatar_image_id',
            'article',
            'avatar_image_id',
            'image',
            'id',
            'RESTRICT'
        );

        // creates index for column `article_category_id`
        $this->createIndex(
            'idx-article-article_category_id',
            'article',
            'article_category_id'
        );

        // add foreign key for table `article_category`
        $this->addForeignKey(
            'fk-article-article_category_id',
            'article',
            'article_category_id',
            'article_category',
            'id',
            'RESTRICT'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-article-creator_id',
            'article'
        );

        // drops index for column `creator_id`
        $this->dropIndex(
            'idx-article-creator_id',
            'article'
        );

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-article-updater_id',
            'article'
        );

        // drops index for column `updater_id`
        $this->dropIndex(
            'idx-article-updater_id',
            'article'
        );

        // drops foreign key for table `image`
        $this->dropForeignKey(
            'fk-article-avatar_image_id',
            'article'
        );

        // drops index for column `avatar_image_id`
        $this->dropIndex(
            'idx-article-avatar_image_id',
            'article'
        );

        // drops foreign key for table `article_category`
        $this->dropForeignKey(
            'fk-article-article_category_id',
            'article'
        );

        // drops index for column `article_category_id`
        $this->dropIndex(
            'idx-article-article_category_id',
            'article'
        );

        $this->dropTable('article');
    }
}
