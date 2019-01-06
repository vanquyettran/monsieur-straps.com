<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_category_to_banner_image`.
 * Has foreign keys to the tables:
 *
 * - `product_category`
 * - `image`
 */
class m180421_174611_create_junction_table_for_product_category_and_image_tables extends Migration
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

        $this->createTable('product_category_to_banner_image', [
            'product_category_id' => $this->integer(),
            'banner_image_id' => $this->integer(),
            'sort_order' => $this->integer()->notNull()->defaultValue(0),
            'PRIMARY KEY(product_category_id, banner_image_id)',
        ], $tableOptions);

        // creates index for column `product_category_id`
        $this->createIndex(
            'idx-product_category_to_banner_image-product_category_id',
            'product_category_to_banner_image',
            'product_category_id'
        );

        // add foreign key for table `product_category`
        $this->addForeignKey(
            'fk-product_category_to_banner_image-product_category_id',
            'product_category_to_banner_image',
            'product_category_id',
            'product_category',
            'id',
            'RESTRICT'
        );

        // creates index for column `banner_image_id`
        $this->createIndex(
            'idx-product_category_to_banner_image-banner_image_id',
            'product_category_to_banner_image',
            'banner_image_id'
        );

        // add foreign key for table `image`
        $this->addForeignKey(
            'fk-product_category_to_banner_image-banner_image_id',
            'product_category_to_banner_image',
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
        // drops foreign key for table `product_category`
        $this->dropForeignKey(
            'fk-product_category_to_banner_image-product_category_id',
            'product_category_to_banner_image'
        );

        // drops index for column `product_category_id`
        $this->dropIndex(
            'idx-product_category_to_banner_image-product_category_id',
            'product_category_to_banner_image'
        );

        // drops foreign key for table `image`
        $this->dropForeignKey(
            'fk-product_category_to_banner_image-banner_image_id',
            'product_category_to_banner_image'
        );

        // drops index for column `banner_image_id`
        $this->dropIndex(
            'idx-product_category_to_banner_image-banner_image_id',
            'product_category_to_banner_image'
        );

        $this->dropTable('product_category_to_banner_image');
    }
}
