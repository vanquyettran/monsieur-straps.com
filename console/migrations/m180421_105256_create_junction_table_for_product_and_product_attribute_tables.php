<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_to_product_attribute`.
 * Has foreign keys to the tables:
 *
 * - `product`
 * - `product_attribute`
 */
class m180421_105256_create_junction_table_for_product_and_product_attribute_tables extends Migration
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

        $this->createTable('product_to_product_attribute', [
            'product_id' => $this->integer(),
            'product_attribute_id' => $this->integer(),
            'PRIMARY KEY(product_id, product_attribute_id)',
        ], $tableOptions);

        // creates index for column `product_id`
        $this->createIndex(
            'idx-product_to_product_attribute-product_id',
            'product_to_product_attribute',
            'product_id'
        );

        // add foreign key for table `product`
        $this->addForeignKey(
            'fk-product_to_product_attribute-product_id',
            'product_to_product_attribute',
            'product_id',
            'product',
            'id',
            'CASCADE'
        );

        // creates index for column `product_attribute_id`
        $this->createIndex(
            'idx-product_to_product_attribute-product_attribute_id',
            'product_to_product_attribute',
            'product_attribute_id'
        );

        // add foreign key for table `product_attribute`
        $this->addForeignKey(
            'fk-product_to_product_attribute-product_attribute_id',
            'product_to_product_attribute',
            'product_attribute_id',
            'product_attribute',
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
            'fk-product_to_product_attribute-product_id',
            'product_to_product_attribute'
        );

        // drops index for column `product_id`
        $this->dropIndex(
            'idx-product_to_product_attribute-product_id',
            'product_to_product_attribute'
        );

        // drops foreign key for table `product_attribute`
        $this->dropForeignKey(
            'fk-product_to_product_attribute-product_attribute_id',
            'product_to_product_attribute'
        );

        // drops index for column `product_attribute_id`
        $this->dropIndex(
            'idx-product_to_product_attribute-product_attribute_id',
            'product_to_product_attribute'
        );

        $this->dropTable('product_to_product_attribute');
    }
}
