<?php

use yii\db\Migration;

/**
 * Handles adding description to table `product_category`.
 */
class m180818_073736_add_description_column_to_product_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('product_category', 'description', $this->string(511)->after('meta_description'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('product_category', 'description');
    }
}
