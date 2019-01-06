<?php

use yii\db\Migration;

/**
 * Handles adding filter to table `banner`.
 */
class m181202_171649_add_filter_column_to_banner_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('banner', 'filter', $this->string());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('banner', 'filter');
    }
}
