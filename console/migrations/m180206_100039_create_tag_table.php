<?php

use yii\db\Migration;

/**
 * Handles the creation of table `tag`.
 * Has foreign keys to the tables:
 *
 * - `user`
 * - `user`
 * - `image`
 */
class m180206_100039_create_tag_table extends Migration
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

        $this->createTable('tag', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull()->unique(),
            'heading' => $this->string(),
            'page_title' => $this->string(),
            'meta_title' => $this->string(),
            'meta_keywords' => $this->string(511),
            'meta_description' => $this->string(511),
            'long_description' => $this->text(),
            'active' => $this->smallInteger()->notNull()->defaultValue(0),
            'visible' => $this->smallInteger()->notNull()->defaultValue(0),
            'featured' => $this->smallInteger()->notNull()->defaultValue(0),
            'allow_indexing' => $this->smallInteger()->notNull()->defaultValue(0),
            'allow_following' => $this->smallInteger()->notNull()->defaultValue(0),
            'sort_order' => $this->integer()->notNull()->defaultValue(0),
            'created_time' => $this->dateTime()->notNull(),
            'updated_time' => $this->dateTime()->notNull(),
            'creator_id' => $this->integer()->notNull(),
            'updater_id' => $this->integer()->notNull(),
            'avatar_image_id' => $this->integer(),
        ], $tableOptions);

        // creates index for column `creator_id`
        $this->createIndex(
            'idx-tag-creator_id',
            'tag',
            'creator_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-tag-creator_id',
            'tag',
            'creator_id',
            'user',
            'id',
            'RESTRICT'
        );

        // creates index for column `updater_id`
        $this->createIndex(
            'idx-tag-updater_id',
            'tag',
            'updater_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-tag-updater_id',
            'tag',
            'updater_id',
            'user',
            'id',
            'RESTRICT'
        );

        // creates index for column `avatar_image_id`
        $this->createIndex(
            'idx-tag-avatar_image_id',
            'tag',
            'avatar_image_id'
        );

        // add foreign key for table `image`
        $this->addForeignKey(
            'fk-tag-avatar_image_id',
            'tag',
            'avatar_image_id',
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
        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-tag-creator_id',
            'tag'
        );

        // drops index for column `creator_id`
        $this->dropIndex(
            'idx-tag-creator_id',
            'tag'
        );

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-tag-updater_id',
            'tag'
        );

        // drops index for column `updater_id`
        $this->dropIndex(
            'idx-tag-updater_id',
            'tag'
        );

        // drops foreign key for table `image`
        $this->dropForeignKey(
            'fk-tag-avatar_image_id',
            'tag'
        );

        // drops index for column `avatar_image_id`
        $this->dropIndex(
            'idx-tag-avatar_image_id',
            'tag'
        );

        $this->dropTable('tag');
    }
}
