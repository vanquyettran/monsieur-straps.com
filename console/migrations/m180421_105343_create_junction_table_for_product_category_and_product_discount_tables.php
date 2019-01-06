<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_category_to_product_discount`.
 * Has foreign keys to the tables:
 *
 * - `product_category`
 * - `product_discount`
 */
class m180421_105343_create_junction_table_for_product_category_and_product_discount_tables extends Migration
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

        $this->createTable('product_category_to_product_discount', [
            'product_category_id' => $this->integer(),
            'product_discount_id' => $this->integer(),
            'PRIMARY KEY(product_category_id, product_discount_id)',
        ], $tableOptions);

        // creates index for column `product_category_id`
        $this->createIndex(
            'idx-product_category_to_product_discount-product_category_id',
            'product_category_to_product_discount',
            'product_category_id'
        );

        // add foreign key for table `product_category`
        $this->addForeignKey(
            'fk-product_category_to_product_discount-product_category_id',
            'product_category_to_product_discount',
            'product_category_id',
            'product_category',
            'id',
            'CASCADE'
        );

        // creates index for column `product_discount_id`
        $this->createIndex(
            'idx-product_category_to_product_discount-product_discount_id',
            'product_category_to_product_discount',
            'product_discount_id'
        );

        // add foreign key for table `product_discount`
        $this->addForeignKey(
            'fk-product_category_to_product_discount-product_discount_id',
            'product_category_to_product_discount',
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
        // drops foreign key for table `product_category`
        $this->dropForeignKey(
            'fk-product_category_to_product_discount-product_category_id',
            'product_category_to_product_discount'
        );

        // drops index for column `product_category_id`
        $this->dropIndex(
            'idx-product_category_to_product_discount-product_category_id',
            'product_category_to_product_discount'
        );

        // drops foreign key for table `product_discount`
        $this->dropForeignKey(
            'fk-product_category_to_product_discount-product_discount_id',
            'product_category_to_product_discount'
        );

        // drops index for column `product_discount_id`
        $this->dropIndex(
            'idx-product_category_to_product_discount-product_discount_id',
            'product_category_to_product_discount'
        );

        $this->dropTable('product_category_to_product_discount');
    }
}
