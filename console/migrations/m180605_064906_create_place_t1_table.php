<?php

use yii\db\Migration;

/**
 * Handles the creation of table `place_t1`.
 */
class m180605_064906_create_place_t1_table extends Migration
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

        $this->createTable('place_t1', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'type' => $this->smallInteger()->notNull(),
            'latitude' => $this->decimal(17, 15),
            'longitude' => $this->decimal(18, 15),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('place_t1');
    }
}
