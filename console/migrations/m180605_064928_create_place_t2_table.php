<?php

use yii\db\Migration;

/**
 * Handles the creation of table `place_t2`.
 * Has foreign keys to the tables:
 *
 * - `place_t1`
 */
class m180605_064928_create_place_t2_table extends Migration
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

        $this->createTable('place_t2', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'type' => $this->smallInteger()->notNull(),
            'place_t1_id' => $this->integer()->notNull(),
            'latitude' => $this->decimal(17, 15),
            'longitude' => $this->decimal(18, 15),
        ], $tableOptions);

        // creates index for column `place_t1_id`
        $this->createIndex(
            'idx-place_t2-place_t1_id',
            'place_t2',
            'place_t1_id'
        );

        // add foreign key for table `place_t1`
        $this->addForeignKey(
            'fk-place_t2-place_t1_id',
            'place_t2',
            'place_t1_id',
            'place_t1',
            'id',
            'RESTRICT'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `place_t1`
        $this->dropForeignKey(
            'fk-place_t2-place_t1_id',
            'place_t2'
        );

        // drops index for column `place_t1_id`
        $this->dropIndex(
            'idx-place_t2-place_t1_id',
            'place_t2'
        );

        $this->dropTable('place_t2');
    }
}
