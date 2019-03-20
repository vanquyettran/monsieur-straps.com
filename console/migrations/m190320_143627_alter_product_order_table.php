<?php

use yii\db\Migration;

/**
 * Class m190320_143627_alter_product_order_table
 */
class m190320_143627_alter_product_order_table extends Migration
{
    public function up()
    {
        $this->alterColumn('product_order', 'customer_phone', $this->string(255));
        $this->addColumn('product_order', 'payment_gateway_name', $this->string(255));
        $this->addColumn('product_order', 'payment_gateway_response', $this->text());
    }

    public function down()
    {
        $this->dropColumn('product_order', 'payment_gateway_response');
        $this->dropColumn('product_order', 'payment_gateway_name');
        $this->alterColumn('product_order', 'customer_phone', $this->string(255)->notNull());
    }
}
