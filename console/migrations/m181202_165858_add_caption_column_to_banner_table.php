<?php

use yii\db\Migration;

/**
 * Handles adding caption to table `banner`.
 */
class m181202_165858_add_caption_column_to_banner_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('banner', 'caption', $this->string(2047));
        $this->dropColumn('banner', 'link');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('banner', 'caption');
        $this->addColumn('banner', 'link', $this->string(511));
    }
}
