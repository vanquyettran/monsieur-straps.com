<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_order`.
 * Has foreign keys to the tables:
 *
 * - `place_t1`
 * - `place_t2`
 * - `place_t3`
 * - `user`
 */
class m180814_180733_create_product_order_table extends Migration
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

        $this->createTable('product_order', [
            'id' => $this->primaryKey(),
            'total_value' => $this->integer()->notNull(),
            'delivery_fee' => $this->integer()->notNull(),
            'customer_name' => $this->string(255)->notNull(),
            'customer_phone' => $this->string(255)->notNull(),
            'customer_email' => $this->string(255),
            'customer_address' => $this->string(255)->notNull(),
            'customer_place_t1_id' => $this->integer(),
            'customer_place_t2_id' => $this->integer(),
            'customer_place_t3_id' => $this->integer(),
            'status' => $this->smallInteger()->notNull(),
            'created_time' => $this->dateTime()->notNull(),
            'updated_time' => $this->dateTime()->notNull(),
            'updated_user' => $this->integer(),
        ], $tableOptions);

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

        // creates index for column `updated_user`
        $this->createIndex(
            'idx-product_order-updated_user',
            'product_order',
            'updated_user'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-product_order-updated_user',
            'product_order',
            'updated_user',
            'user',
            'id',
            'RESTRICT'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
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

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-product_order-updated_user',
            'product_order'
        );

        // drops index for column `updated_user`
        $this->dropIndex(
            'idx-product_order-updated_user',
            'product_order'
        );

        $this->dropTable('product_order');
    }
}
