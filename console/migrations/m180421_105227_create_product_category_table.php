<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_category`.
 * Has foreign keys to the tables:
 *
 * - `user`
 * - `user`
 * - `image`
 * - `product_category`
 */
class m180421_105227_create_product_category_table extends Migration
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

        $this->createTable('product_category', [
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
            'displaying_areas' => $this->string(),
            'type' => $this->smallInteger()->notNull(),
            'created_time' => $this->dateTime()->notNull(),
            'updated_time' => $this->dateTime()->notNull(),
            'creator_id' => $this->integer()->notNull(),
            'updater_id' => $this->integer()->notNull(),
            'avatar_image_id' => $this->integer()->notNull(),
            'parent_id' => $this->integer(),
        ], $tableOptions);

        // creates index for column `creator_id`
        $this->createIndex(
            'idx-product_category-creator_id',
            'product_category',
            'creator_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-product_category-creator_id',
            'product_category',
            'creator_id',
            'user',
            'id',
            'RESTRICT'
        );

        // creates index for column `updater_id`
        $this->createIndex(
            'idx-product_category-updater_id',
            'product_category',
            'updater_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-product_category-updater_id',
            'product_category',
            'updater_id',
            'user',
            'id',
            'RESTRICT'
        );

        // creates index for column `avatar_image_id`
        $this->createIndex(
            'idx-product_category-avatar_image_id',
            'product_category',
            'avatar_image_id'
        );

        // add foreign key for table `image`
        $this->addForeignKey(
            'fk-product_category-avatar_image_id',
            'product_category',
            'avatar_image_id',
            'image',
            'id',
            'RESTRICT'
        );

        // creates index for column `parent_id`
        $this->createIndex(
            'idx-product_category-parent_id',
            'product_category',
            'parent_id'
        );

        // add foreign key for table `product_category`
        $this->addForeignKey(
            'fk-product_category-parent_id',
            'product_category',
            'parent_id',
            'product_category',
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
            'fk-product_category-creator_id',
            'product_category'
        );

        // drops index for column `creator_id`
        $this->dropIndex(
            'idx-product_category-creator_id',
            'product_category'
        );

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-product_category-updater_id',
            'product_category'
        );

        // drops index for column `updater_id`
        $this->dropIndex(
            'idx-product_category-updater_id',
            'product_category'
        );

        // drops foreign key for table `image`
        $this->dropForeignKey(
            'fk-product_category-avatar_image_id',
            'product_category'
        );

        // drops index for column `avatar_image_id`
        $this->dropIndex(
            'idx-product_category-avatar_image_id',
            'product_category'
        );

        // drops foreign key for table `product_category`
        $this->dropForeignKey(
            'fk-product_category-parent_id',
            'product_category'
        );

        // drops index for column `parent_id`
        $this->dropIndex(
            'idx-product_category-parent_id',
            'product_category'
        );

        $this->dropTable('product_category');
    }
}
