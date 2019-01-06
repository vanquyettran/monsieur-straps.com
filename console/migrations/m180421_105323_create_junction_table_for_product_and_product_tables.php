<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_to_related_product`.
 * Has foreign keys to the tables:
 *
 * - `product`
 */
class m180421_105323_create_junction_table_for_product_and_product_tables extends Migration
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

        $this->createTable('product_to_related_product', [
            'product_id' => $this->integer(),
            'related_product_id' => $this->integer(),
            'PRIMARY KEY(product_id, related_product_id)',
        ], $tableOptions);

        // creates index for column `product_id`
        $this->createIndex(
            'idx-product_to_related_product-product_id',
            'product_to_related_product',
            'product_id'
        );

        // add foreign key for table `product`
        $this->addForeignKey(
            'fk-product_to_related_product-product_id',
            'product_to_related_product',
            'product_id',
            'product',
            'id',
            'CASCADE'
        );

        // creates index for column `related_product_id`
        $this->createIndex(
            'idx-product_to_related_product-related_product_id',
            'product_to_related_product',
            'related_product_id'
        );

        // add foreign key for table `product`
        $this->addForeignKey(
            'fk-product_to_related_product-related_product_id',
            'product_to_related_product',
            'related_product_id',
            'product',
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
            'fk-product_to_related_product-related_product_id',
            'product_to_related_product'
        );

        // drops index for column `related_product_id`
        $this->dropIndex(
            'idx-product_to_related_product-related_product_id',
            'product_to_related_product'
        );

        // drops foreign key for table `product`
        $this->dropForeignKey(
            'fk-product_to_related_product-product_id',
            'product_to_related_product'
        );

        // drops index for column `product_id`
        $this->dropIndex(
            'idx-product_to_related_product-product_id',
            'product_to_related_product'
        );

        $this->dropTable('product_to_related_product');
    }
}
