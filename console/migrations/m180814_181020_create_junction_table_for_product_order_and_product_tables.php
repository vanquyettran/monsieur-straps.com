<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_order_to_product`.
 * Has foreign keys to the tables:
 *
 * - `product_order`
 * - `product`
 */
class m180814_181020_create_junction_table_for_product_order_and_product_tables extends Migration
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

        $this->createTable('product_order_to_product', [
            'product_order_id' => $this->integer(),
            'product_id' => $this->integer(),
            'product_quantity' => $this->integer()->notNull(),
            'product_code' => $this->string(255)->notNull(),
            'product_name' => $this->string(255)->notNull(),
            'product_price' => $this->integer()->notNull(),
            'product_discounted_price' => $this->integer()->notNull(),
            'PRIMARY KEY(product_order_id, product_id)',
        ], $tableOptions);

        // creates index for column `product_order_id`
        $this->createIndex(
            'idx-product_order_to_product-product_order_id',
            'product_order_to_product',
            'product_order_id'
        );

        // add foreign key for table `product_order`
        $this->addForeignKey(
            'fk-product_order_to_product-product_order_id',
            'product_order_to_product',
            'product_order_id',
            'product_order',
            'id',
            'RESTRICT'
        );

        // creates index for column `product_id`
        $this->createIndex(
            'idx-product_order_to_product-product_id',
            'product_order_to_product',
            'product_id'
        );

        // add foreign key for table `product`
        $this->addForeignKey(
            'fk-product_order_to_product-product_id',
            'product_order_to_product',
            'product_id',
            'product',
            'id',
            'RESTRICT'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `product_order`
        $this->dropForeignKey(
            'fk-product_order_to_product-product_order_id',
            'product_order_to_product'
        );

        // drops index for column `product_order_id`
        $this->dropIndex(
            'idx-product_order_to_product-product_order_id',
            'product_order_to_product'
        );

        // drops foreign key for table `product`
        $this->dropForeignKey(
            'fk-product_order_to_product-product_id',
            'product_order_to_product'
        );

        // drops index for column `product_id`
        $this->dropIndex(
            'idx-product_order_to_product-product_id',
            'product_order_to_product'
        );

        $this->dropTable('product_order_to_product');
    }
}
