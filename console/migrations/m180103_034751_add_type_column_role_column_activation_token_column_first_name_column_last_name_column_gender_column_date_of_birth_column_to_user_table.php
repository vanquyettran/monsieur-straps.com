<?php

use yii\db\Migration;

/**
 * Handles adding type_column_role_column_activation_token_column_first_name_column_last_name_column_gender_column_date_of_birth to table `user`.
 */
class m180103_034751_add_type_column_role_column_activation_token_column_first_name_column_last_name_column_gender_column_date_of_birth_column_to_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('user', 'type', $this->smallInteger()->notNull()->defaultValue(0)->after('email'));
        $this->addColumn('user', 'role', $this->smallInteger()->notNull()->defaultValue(0)->after('status'));
        $this->addColumn('user', 'activation_token', $this->string()->unique()->after('password_reset_token'));
        $this->addColumn('user', 'first_name', $this->string());
        $this->addColumn('user', 'last_name', $this->string());
        $this->addColumn('user', 'gender', $this->smallInteger());
        $this->addColumn('user', 'date_of_birth', $this->integer());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('user', 'type');
        $this->dropColumn('user', 'role');
        $this->dropColumn('user', 'activation_token');
        $this->dropColumn('user', 'first_name');
        $this->dropColumn('user', 'last_name');
        $this->dropColumn('user', 'gender');
        $this->dropColumn('user', 'date_of_birth');
    }
}
