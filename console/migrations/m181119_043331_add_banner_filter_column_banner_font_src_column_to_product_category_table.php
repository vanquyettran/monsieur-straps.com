<?php

use yii\db\Migration;

/**
 * Handles adding banner_filter_column_banner_font_src to table `product_category`.
 */
class m181119_043331_add_banner_filter_column_banner_font_src_column_to_product_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('product_category', 'banner_filter', $this->string(255));
        $this->addColumn('product_category', 'banner_font_src', $this->string(2047));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('product_category', 'banner_filter');
        $this->dropColumn('product_category', 'banner_font_src');
    }
}
