<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_discount`.
 */
class m180421_105329_create_product_discount_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('product_discount', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'occasion' => $this->text(),
            'percentage' => $this->smallInteger(2)->notNull(),
            'start_time' => $this->dateTime()->notNull(),
            'end_time' => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('product_discount');
    }
}
