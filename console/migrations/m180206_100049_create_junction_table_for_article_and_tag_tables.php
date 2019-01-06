<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_to_tag`.
 * Has foreign keys to the tables:
 *
 * - `article`
 * - `tag`
 */
class m180206_100049_create_junction_table_for_article_and_tag_tables extends Migration
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

        $this->createTable('article_to_tag', [
            'article_id' => $this->integer(),
            'tag_id' => $this->integer(),
            'PRIMARY KEY(article_id, tag_id)',
        ], $tableOptions);

        // creates index for column `article_id`
        $this->createIndex(
            'idx-article_to_tag-article_id',
            'article_to_tag',
            'article_id'
        );

        // add foreign key for table `article`
        $this->addForeignKey(
            'fk-article_to_tag-article_id',
            'article_to_tag',
            'article_id',
            'article',
            'id',
            'CASCADE'
        );

        // creates index for column `tag_id`
        $this->createIndex(
            'idx-article_to_tag-tag_id',
            'article_to_tag',
            'tag_id'
        );

        // add foreign key for table `tag`
        $this->addForeignKey(
            'fk-article_to_tag-tag_id',
            'article_to_tag',
            'tag_id',
            'tag',
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
            'fk-article_to_tag-article_id',
            'article_to_tag'
        );

        // drops index for column `article_id`
        $this->dropIndex(
            'idx-article_to_tag-article_id',
            'article_to_tag'
        );

        // drops foreign key for table `tag`
        $this->dropForeignKey(
            'fk-article_to_tag-tag_id',
            'article_to_tag'
        );

        // drops index for column `tag_id`
        $this->dropIndex(
            'idx-article_to_tag-tag_id',
            'article_to_tag'
        );

        $this->dropTable('article_to_tag');
    }
}
