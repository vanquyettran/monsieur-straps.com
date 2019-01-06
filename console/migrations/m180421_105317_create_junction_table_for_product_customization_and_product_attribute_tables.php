<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_customization_to_product_attribute`.
 * Has foreign keys to the tables:
 *
 * - `product_customization`
 * - `product_attribute`
 */
class m180421_105317_create_junction_table_for_product_customization_and_product_attribute_tables extends Migration
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

        $this->createTable('product_customization_to_product_attribute', [
            'product_customization_id' => $this->integer(),
            'product_attribute_id' => $this->integer(),
            'PRIMARY KEY(product_customization_id, product_attribute_id)',
        ], $tableOptions);

        // creates index for column `product_customization_id`
        $this->createIndex(
            'idx-pro_cus_to_pro_att-product_customization_id',
            'product_customization_to_product_attribute',
            'product_customization_id'
        );

        // add foreign key for table `product_customization`
        $this->addForeignKey(
            'fk-pro_cus_to_pro_att-product_customization_id',
            'product_customization_to_product_attribute',
            'product_customization_id',
            'product_customization',
            'id',
            'RESTRICT'
        );

        // creates index for column `product_attribute_id`
        $this->createIndex(
            'idx-pro_cus_to_pro_att-product_attribute_id',
            'product_customization_to_product_attribute',
            'product_attribute_id'
        );

        // add foreign key for table `product_attribute`
        $this->addForeignKey(
            'fk-pro_cus_to_pro_att-product_attribute_id',
            'product_customization_to_product_attribute',
            'product_attribute_id',
            'product_attribute',
            'id',
            'RESTRICT'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `product_customization`
        $this->dropForeignKey(
            'fk-pro_cus_to_pro_att-product_customization_id',
            'product_customization_to_product_attribute'
        );

        // drops index for column `product_customization_id`
        $this->dropIndex(
            'idx-pro_cus_to_pro_att-product_customization_id',
            'product_customization_to_product_attribute'
        );

        // drops foreign key for table `product_attribute`
        $this->dropForeignKey(
            'fk-pro_cus_to_pro_att-product_attribute_id',
            'product_customization_to_product_attribute'
        );

        // drops index for column `product_attribute_id`
        $this->dropIndex(
            'idx-pro_cus_to_pro_att-product_attribute_id',
            'product_customization_to_product_attribute'
        );

        $this->dropTable('product_customization_to_product_attribute');
    }
}
