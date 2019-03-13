<?php

use yii\db\Migration;

/**
 * Handles dropping customer_place_t1_id_column_customer_place_t2_id_column_customer_place_t3_id from table `product_order`.
 * Has foreign keys to the tables:
 *
 * - `place_t1`
 * - `place_t2`
 * - `place_t3`
 */
class m190313_175312_drop_customer_place_t1_id_column_customer_place_t2_id_column_customer_place_t3_id_column_from_product_order_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        // drops foreign key for table `place_t1`
        $this->dropForeignKey(
            'fk-product_order-customer_place_t1_id',
            'product_order'
        );

        // drops index for column `customer_place_t1_id`
        $this->dropIndex(
            'idx-product_order-customer_place_t1_id',
            'product_order'
        );

        // drops foreign key for table `place_t2`
        $this->dropForeignKey(
            'fk-product_order-customer_place_t2_id',
            'product_order'
        );

        // drops index for column `customer_place_t2_id`
        $this->dropIndex(
            'idx-product_order-customer_place_t2_id',
            'product_order'
        );

        // drops foreign key for table `place_t3`
        $this->dropForeignKey(
            'fk-product_order-customer_place_t3_id',
            'product_order'
        );

        // drops index for column `customer_place_t3_id`
        $this->dropIndex(
            'idx-product_order-customer_place_t3_id',
            'product_order'
        );

        $this->dropColumn('product_order', 'customer_place_t1_id');
        $this->dropColumn('product_order', 'customer_place_t2_id');
        $this->dropColumn('product_order', 'customer_place_t3_id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->addColumn('product_order', 'customer_place_t1_id', $this->integer());
        $this->addColumn('product_order', 'customer_place_t2_id', $this->integer());
        $this->addColumn('product_order', 'customer_place_t3_id', $this->integer());

        // creates index for column `customer_place_t1_id`
        $this->createIndex(
            'idx-product_order-customer_place_t1_id',
            'product_order',
            'customer_place_t1_id'
        );

        // add foreign key for table `place_t1`
        $this->addForeignKey(
            'fk-product_order-customer_place_t1_id',
            'product_order',
            'customer_place_t1_id',
            'place_t1',
            'id',
            'RESTRICT'
        );

        // creates index for column `customer_place_t2_id`
        $this->createIndex(
            'idx-product_order-customer_place_t2_id',
            'product_order',
            'customer_place_t2_id'
        );

        // add foreign key for table `place_t2`
        $this->addForeignKey(
            'fk-product_order-customer_place_t2_id',
            'product_order',
            'customer_place_t2_id',
            'place_t2',
            'id',
            'RESTRICT'
        );

        // creates index for column `customer_place_t3_id`
        $this->createIndex(
            'idx-product_order-customer_place_t3_id',
            'product_order',
            'customer_place_t3_id'
        );

        // add foreign key for table `place_t3`
        $this->addForeignKey(
            'fk-product_order-customer_place_t3_id',
            'product_order',
            'customer_place_t3_id',
            'place_t3',
            'id',
            'RESTRICT'
        );
    }
}
