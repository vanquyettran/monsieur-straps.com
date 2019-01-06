<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_to_product_discount`.
 * Has foreign keys to the tables:
 *
 * - `product`
 * - `product_discount`
 */
class m180421_105335_create_junction_table_for_product_and_product_discount_tables extends Migration
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

        $this->createTable('product_to_product_discount', [
            'product_id' => $this->integer(),
            'product_discount_id' => $this->integer(),
            'PRIMARY KEY(product_id, product_discount_id)',
        ], $tableOptions);

        // creates index for column `product_id`
        $this->createIndex(
            'idx-product_to_product_discount-product_id',
            'product_to_product_discount',
            'product_id'
        );

        // add foreign key for table `product`
        $this->addForeignKey(
            'fk-product_to_product_discount-product_id',
            'product_to_product_discount',
            'product_id',
            'product',
            'id',
            'CASCADE'
        );

        // creates index for column `product_discount_id`
        $this->createIndex(
            'idx-product_to_product_discount-product_discount_id',
            'product_to_product_discount',
            'product_discount_id'
        );

        // add foreign key for table `product_discount`
        $this->addForeignKey(
            'fk-product_to_product_discount-product_discount_id',
            'product_to_product_discount',
            'product_discount_id',
            'product_discount',
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
            'fk-product_to_product_discount-product_id',
            'product_to_product_discount'
        );

        // drops index for column `product_id`
        $this->dropIndex(
            'idx-product_to_product_discount-product_id',
            'product_to_product_discount'
        );

        // drops foreign key for table `product_discount`
        $this->dropForeignKey(
            'fk-product_to_product_discount-product_discount_id',
            'product_to_product_discount'
        );

        // drops index for column `product_discount_id`
        $this->dropIndex(
            'idx-product_to_product_discount-product_discount_id',
            'product_to_product_discount'
        );

        $this->dropTable('product_to_product_discount');
    }
}
