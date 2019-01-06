<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_to_related_article`.
 * Has foreign keys to the tables:
 *
 * - `article`
 * - `related_article`
 */
class m180207_021319_create_junction_table_for_article_and_related_article_tables extends Migration
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

        $this->createTable('article_to_related_article', [
            'article_id' => $this->integer(),
            'related_article_id' => $this->integer(),
            'PRIMARY KEY(article_id, related_article_id)',
        ], $tableOptions);

        // creates index for column `article_id`
        $this->createIndex(
            'idx-article_to_related_article-article_id',
            'article_to_related_article',
            'article_id'
        );

        // add foreign key for table `article`
        $this->addForeignKey(
            'fk-article_to_related_article-article_id',
            'article_to_related_article',
            'article_id',
            'article',
            'id',
            'CASCADE'
        );

        // creates index for column `related_article_id`
        $this->createIndex(
            'idx-article_to_related_article-related_article_id',
            'article_to_related_article',
            'related_article_id'
        );

        // add foreign key for table `related_article`
        $this->addForeignKey(
            'fk-article_to_related_article-related_article_id',
            'article_to_related_article',
            'related_article_id',
            'article',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `article`
        $this->dropForeignKey(
            'fk-article_to_related_article-article_id',
            'article_to_related_article'
        );

        // drops index for column `article_id`
        $this->dropIndex(
            'idx-article_to_related_article-article_id',
            'article_to_related_article'
        );

        // drops foreign key for table `related_article`
        $this->dropForeignKey(
            'fk-article_to_related_article-related_article_id',
            'article_to_related_article'
        );

        // drops index for column `related_article_id`
        $this->dropIndex(
            'idx-article_to_related_article-related_article_id',
            'article_to_related_article'
        );

        $this->dropTable('article_to_related_article');
    }
}
