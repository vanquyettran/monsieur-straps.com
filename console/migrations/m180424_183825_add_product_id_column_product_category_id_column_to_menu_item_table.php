<?php

use yii\db\Migration;

/**
 * Handles adding product_id_column_product_category_id to table `menu_item`.
 * Has foreign keys to the tables:
 *
 * - `product`
 * - `product_category`
 */
class m180424_183825_add_product_id_column_product_category_id_column_to_menu_item_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('menu_item', 'product_category_id', $this->integer()->after('article_id'));
        $this->addColumn('menu_item', 'product_id', $this->integer()->after('product_category_id'));

        // creates index for column `product_id`
        $this->createIndex(
            'idx-menu_item-product_id',
            'menu_item',
            'product_id'
        );

        // add foreign key for table `product`
        $this->addForeignKey(
            'fk-menu_item-product_id',
            'menu_item',
            'product_id',
            'product',
            'id',
            'RESTRICT'
        );

        // creates index for column `product_category_id`
        $this->createIndex(
            'idx-menu_item-product_category_id',
            'menu_item',
            'product_category_id'
        );

        // add foreign key for table `product_category`
        $this->addForeignKey(
            'fk-menu_item-product_category_id',
            'menu_item',
            'product_category_id',
            'product_category',
            'id',
            'RESTRICT'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `product`
        $this->dropForeignKey(
            'fk-menu_item-product_id',
            'menu_item'
        );

        // drops index for column `product_id`
        $this->dropIndex(
            'idx-menu_item-product_id',
            'menu_item'
        );

        // drops foreign key for table `product_category`
        $this->dropForeignKey(
            'fk-menu_item-product_category_id',
            'menu_item'
        );

        // drops index for column `product_category_id`
        $this->dropIndex(
            'idx-menu_item-product_category_id',
            'menu_item'
        );

        $this->dropColumn('menu_item', 'product_id');
        $this->dropColumn('menu_item', 'product_category_id');
    }
}
