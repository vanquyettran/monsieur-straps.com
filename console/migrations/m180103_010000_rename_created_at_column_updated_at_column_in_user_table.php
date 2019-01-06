<?php

use yii\db\Migration;

/**
 * Handles renaming created_at and updated_at columns in table `user`.
 */
class m180103_010000_rename_created_at_column_updated_at_column_in_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->renameColumn('user', 'created_at', 'created_time');
        $this->renameColumn('user', 'updated_at', 'updated_time');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->renameColumn('user', 'created_time', 'created_at');
        $this->renameColumn('user', 'updated_time', 'updated_at');
    }
}
