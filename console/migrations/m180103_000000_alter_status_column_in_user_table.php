<?php

use yii\db\Migration;

/**
 * Handles altering status column in table `user`.
 */
class m180103_000000_alter_status_column_in_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->alterColumn('user', 'status', $this->smallInteger()->notNull()->defaultValue(0));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->alterColumn('user', 'status', $this->smallInteger()->notNull()->defaultValue(10));
    }
}
