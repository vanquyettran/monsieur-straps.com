<?php

use yii\db\Migration;

/**
 * Handles altering status column in table `banner`.
 */
class m180120_030004_alter_start_time_column_end_time_column_in_banner_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->alterColumn('banner', 'start_time', $this->dateTime()->notNull());
        $this->alterColumn('banner', 'end_time', $this->dateTime()->notNull());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->alterColumn('banner', 'start_time', $this->integer()->notNull());
        $this->alterColumn('banner', 'end_time', $this->integer()->notNull());
    }
}
