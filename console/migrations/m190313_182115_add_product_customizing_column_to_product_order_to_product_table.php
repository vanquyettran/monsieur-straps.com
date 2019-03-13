<?php

use yii\db\Migration;

/**
 * Handles adding product_customizing to table `product_order_to_product`.
 */
class m190313_182115_add_product_customizing_column_to_product_order_to_product_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('product_order_to_product', 'product_customizing', $this->string(1023));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('product_order_to_product', 'product_customizing');
    }
}
