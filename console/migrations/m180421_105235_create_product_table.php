<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product`.
 * Has foreign keys to the tables:
 *
 * - `user`
 * - `user`
 * - `image`
 * - `product_category`
 */
class m180421_105235_create_product_table extends Migration
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

        $this->createTable('product', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull()->unique(),
            'heading' => $this->string(),
            'page_title' => $this->string(),
            'meta_title' => $this->string(),
            'meta_keywords' => $this->string(511),
            'meta_description' => $this->string(511),
            'description' => $this->string(511),
            'code' => $this->string()->notNull()->unique(),
            'price' => $this->integer()->notNull(),
            'long_description' => $this->text(),
            'details' => $this->text(),
            'active' => $this->smallInteger()->notNull()->defaultValue(0),
            'visible' => $this->smallInteger()->notNull()->defaultValue(0),
            'featured' => $this->smallInteger()->notNull()->defaultValue(0),
            'allow_indexing' => $this->smallInteger()->notNull()->defaultValue(0),
            'allow_following' => $this->smallInteger()->notNull()->defaultValue(0),
            'production_status' => $this->smallInteger()->notNull(),
            'sort_order' => $this->integer()->notNull()->defaultValue(0),
            'published_time' => $this->dateTime()->notNull(),
            'created_time' => $this->dateTime()->notNull(),
            'updated_time' => $this->dateTime()->notNull(),
            'view_count' => $this->integer()->notNull()->defaultValue(0),
            'creator_id' => $this->integer()->notNull(),
            'updater_id' => $this->integer()->notNull(),
            'avatar_image_id' => $this->integer()->notNull(),
            'product_category_id' => $this->integer()->notNull(),
        ], $tableOptions);

        // creates index for column `creator_id`
        $this->createIndex(
            'idx-product-creator_id',
            'product',
            'creator_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-product-creator_id',
            'product',
            'creator_id',
            'user',
            'id',
            'RESTRICT'
        );

        // creates index for column `updater_id`
        $this->createIndex(
            'idx-product-updater_id',
            'product',
            'updater_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-product-updater_id',
            'product',
            'updater_id',
            'user',
            'id',
            'RESTRICT'
        );

        // creates index for column `avatar_image_id`
        $this->createIndex(
            'idx-product-avatar_image_id',
            'product',
            'avatar_image_id'
        );

        // add foreign key for table `image`
        $this->addForeignKey(
            'fk-product-avatar_image_id',
            'product',
            'avatar_image_id',
            'image',
            'id',
            'RESTRICT'
        );

        // creates index for column `product_category_id`
        $this->createIndex(
            'idx-product-product_category_id',
            'product',
            'product_category_id'
        );

        // add foreign key for table `product_category`
        $this->addForeignKey(
            'fk-product-product_category_id',
            'product',
            'product_category_id',
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
            'fk-product-creator_id',
            'product'
        );

        // drops index for column `creator_id`
        $this->dropIndex(
            'idx-product-creator_id',
            'product'
        );

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-product-updater_id',
            'product'
        );

        // drops index for column `updater_id`
        $this->dropIndex(
            'idx-product-updater_id',
            'product'
        );

        // drops foreign key for table `image`
        $this->dropForeignKey(
            'fk-product-avatar_image_id',
            'product'
        );

        // drops index for column `avatar_image_id`
        $this->dropIndex(
            'idx-product-avatar_image_id',
            'product'
        );

        // drops foreign key for table `product_category`
        $this->dropForeignKey(
            'fk-product-product_category_id',
            'product'
        );

        // drops index for column `product_category_id`
        $this->dropIndex(
            'idx-product-product_category_id',
            'product'
        );

        $this->dropTable('product');
    }
}
