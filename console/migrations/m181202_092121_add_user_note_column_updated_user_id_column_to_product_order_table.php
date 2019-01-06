<?php

use yii\db\Migration;

/**
 * Handles adding user_note_column_updated_user_id to table `product_order`.
 * Has foreign keys to the tables:
 *
 * - `user`
 */
class m181202_092121_add_user_note_column_updated_user_id_column_to_product_order_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('product_order', 'user_note', $this->string(2047));
        $this->renameColumn('product_order', 'updated_user', 'updated_user_id');

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('product_order', 'user_note');
        $this->renameColumn('product_order', 'updated_user_id', 'updated_user');
    }
}
