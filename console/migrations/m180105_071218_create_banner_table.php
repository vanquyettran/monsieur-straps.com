<?php

use yii\db\Migration;

/**
 * Handles the creation of table `banner`.
 * Has foreign keys to the tables:
 *
 * - `image`
 */
class m180105_071218_create_banner_table extends Migration
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

        $this->createTable('banner', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'link' => $this->string(511),
            'position' => $this->smallInteger()->notNull(),
            'sort_order' => $this->integer()->notNull()->defaultValue(0),
            'start_time' => $this->bigInteger()->notNull(),
            'end_time' => $this->bigInteger()->notNull(),
            'active' => $this->smallInteger()->notNull()->defaultValue(0),
            'image_id' => $this->integer()->notNull(),
        ], $tableOptions);

        // creates index for column `image_id`
        $this->createIndex(
            'idx-banner-image_id',
            'banner',
            'image_id'
        );

        // add foreign key for table `image`
        $this->addForeignKey(
            'fk-banner-image_id',
            'banner',
            'image_id',
            'image',
            'id',
            'RESTRICT'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `image`
        $this->dropForeignKey(
            'fk-banner-image_id',
            'banner'
        );

        // drops index for column `image_id`
        $this->dropIndex(
            'idx-banner-image_id',
            'banner'
        );

        $this->dropTable('banner');
    }
}
