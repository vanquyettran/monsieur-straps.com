<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_to_detailed_image`.
 * Has foreign keys to the tables:
 *
 * - `product`
 * - `image`
 */
class m180421_133514_create_junction_table_for_product_and_image_tables extends Migration
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

        $this->createTable('product_to_detailed_image', [
            'product_id' => $this->integer(),
            'detailed_image_id' => $this->integer(),
            'sort_order' => $this->integer()->notNull()->defaultValue(0),
            'PRIMARY KEY(product_id, detailed_image_id)',
        ], $tableOptions);

        // creates index for column `product_id`
        $this->createIndex(
            'idx-product_to_detailed_image-product_id',
            'product_to_detailed_image',
            'product_id'
        );

        // add foreign key for table `product`
        $this->addForeignKey(
            'fk-product_to_detailed_image-product_id',
            'product_to_detailed_image',
            'product_id',
            'product',
            'id',
            'CASCADE'
        );

        // creates index for column `detailed_image_id`
        $this->createIndex(
            'idx-product_to_detailed_image-detailed_image_id',
            'product_to_detailed_image',
            'detailed_image_id'
        );

        // add foreign key for table `image`
        $this->addForeignKey(
            'fk-product_to_detailed_image-detailed_image_id',
            'product_to_detailed_image',
            'detailed_image_id',
            'image',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `product`
        $this->dropForeignKey(
            'fk-product_to_detailed_image-product_id',
            'product_to_detailed_image'
        );

        // drops index for column `product_id`
        $this->dropIndex(
            'idx-product_to_detailed_image-product_id',
            'product_to_detailed_image'
        );

        // drops foreign key for table `image`
        $this->dropForeignKey(
            'fk-product_to_detailed_image-detailed_image_id',
            'product_to_detailed_image'
        );

        // drops index for column `detailed_image_id`
        $this->dropIndex(
            'idx-product_to_detailed_image-detailed_image_id',
            'product_to_detailed_image'
        );

        $this->dropTable('product_to_detailed_image');
    }
}
