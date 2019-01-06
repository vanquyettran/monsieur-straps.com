<?php

use yii\db\Migration;

/**
 * Handles altering status column in table `user`.
 */
class m180120_030000_alter_created_time_column_updated_time_column_in_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->alterColumn('user', 'created_time', $this->dateTime()->notNull());
        $this->alterColumn('user', 'updated_time', $this->dateTime()->notNull());
        $this->alterColumn('user', 'date_of_birth', $this->date());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->alterColumn('user', 'created_time', $this->integer()->notNull());
        $this->alterColumn('user', 'updated_time', $this->integer()->notNull());
        $this->alterColumn('user', 'date_of_birth', $this->integer());
    }
}
