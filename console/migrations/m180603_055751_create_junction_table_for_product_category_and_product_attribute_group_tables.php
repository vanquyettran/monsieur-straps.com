<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_category_to_product_attribute_group`.
 * Has foreign keys to the tables:
 *
 * - `product_category`
 * - `product_attribute_group`
 */
class m180603_055751_create_junction_table_for_product_category_and_product_attribute_group_tables extends Migration
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

        $this->createTable('product_category_to_product_attribute_group', [
            'product_category_id' => $this->integer(),
            'product_attribute_group_id' => $this->integer(),
            'PRIMARY KEY(product_category_id, product_attribute_group_id)',
        ], $tableOptions);

        // creates index for column `product_category_id`
        $this->createIndex(
            'idx-pro_cat_to_pro_attr_group-product_category_id',
            'product_category_to_product_attribute_group',
            'product_category_id'
        );

        // add foreign key for table `product_category`
        $this->addForeignKey(
            'fk-pro_cat_to_pro_attr_group-product_category_id',
            'product_category_to_product_attribute_group',
            'product_category_id',
            'product_category',
            'id',
            'CASCADE'
        );

        // creates index for column `product_attribute_group_id`
        $this->createIndex(
            'idx-pro_cat_to_pro_attr_group-product_attribute_group_id',
            'product_category_to_product_attribute_group',
            'product_attribute_group_id'
        );

        // add foreign key for table `product_attribute_group`
        $this->addForeignKey(
            'fk-pro_cat_to_pro_attr_group-product_attribute_group_id',
            'product_category_to_product_attribute_group',
            'product_attribute_group_id',
            'product_attribute_group',
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
            'fk-pro_cat_to_pro_attr_group-product_category_id',
            'product_category_to_product_attribute_group'
        );

        // drops index for column `product_category_id`
        $this->dropIndex(
            'idx-pro_cat_to_pro_attr_group-product_category_id',
            'product_category_to_product_attribute_group'
        );

        // drops foreign key for table `product_attribute_group`
        $this->dropForeignKey(
            'fk-pro_cat_to_pro_attr_group-product_attribute_group_id',
            'product_category_to_product_attribute_group'
        );

        // drops index for column `product_attribute_group_id`
        $this->dropIndex(
            'idx-pro_cat_to_pro_attr_group-product_attribute_group_id',
            'product_category_to_product_attribute_group'
        );

        $this->dropTable('product_category_to_product_attribute_group');
    }
}
