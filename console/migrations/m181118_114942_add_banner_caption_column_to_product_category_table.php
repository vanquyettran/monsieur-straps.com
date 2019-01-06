<?php

use yii\db\Migration;

/**
 * Handles adding banner_caption to table `product_category`.
 */
class m181118_114942_add_banner_caption_column_to_product_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('product_category', 'banner_caption', $this->string(2047));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('product_category', 'banner_caption');
    }
}
