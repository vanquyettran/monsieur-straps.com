<?php

use yii\db\Migration;

/**
 * Handles adding customer_address_2 to table `product_order`.
 */
class m190313_175456_add_customer_address_2_column_to_product_order_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('product_order', 'customer_address_2', $this->string()->after('customer_address'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('product_order', 'customer_address_2');
    }
}
