<?php

use yii\db\Migration;

/**
 * Handles adding customer_country_column_customer_province_column_customer_city_column_customer_postal_code to table `product_order`.
 */
class m190313_174828_add_customer_country_column_customer_province_column_customer_city_column_customer_postal_code_column_to_product_order_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('product_order', 'customer_country', $this->string());
        $this->addColumn('product_order', 'customer_province', $this->string());
        $this->addColumn('product_order', 'customer_city', $this->string());
        $this->addColumn('product_order', 'customer_postal_code', $this->string());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('product_order', 'customer_country');
        $this->dropColumn('product_order', 'customer_province');
        $this->dropColumn('product_order', 'customer_city');
        $this->dropColumn('product_order', 'customer_postal_code');
    }
}
