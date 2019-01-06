<?php

use yii\db\Migration;

/**
 * Handles the creation of table `static_page_info`.
 * Has foreign keys to the tables:
 *
 * - `image`
 */
class m180105_090020_create_static_page_info_table extends Migration
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

        $this->createTable('static_page_info', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'route' => $this->string()->notNull(),
            'url_pattern' => $this->string(),
            'heading' => $this->string(),
            'page_title' => $this->string(),
            'meta_title' => $this->string(),
            'meta_keywords' => $this->string(511),
            'meta_description' => $this->string(511),
            'menu_item_label' => $this->string(),
            'long_description' => $this->text(),
            'active' => $this->smallInteger()->notNull()->defaultValue(0),
            'allow_indexing' => $this->smallInteger()->notNull()->defaultValue(0),
            'allow_following' => $this->smallInteger()->notNull()->defaultValue(0),
            'avatar_image_id' => $this->integer(),
        ], $tableOptions);

        $this->createIndex(
            'idx-static_page_info-route-url_pattern',
            'static_page_info',
            ['route', 'url_pattern'],
            true
        );

        // creates index for column `avatar_image_id`
        $this->createIndex(
            'idx-static_page_info-avatar_image_id',
            'static_page_info',
            'avatar_image_id'
        );

        // add foreign key for table `image`
        $this->addForeignKey(
            'fk-static_page_info-avatar_image_id',
            'static_page_info',
            'avatar_image_id',
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
            'fk-static_page_info-avatar_image_id',
            'static_page_info'
        );

        // drops index for column `avatar_image_id`
        $this->dropIndex(
            'idx-static_page_info-avatar_image_id',
            'static_page_info'
        );

        $this->dropIndex(
            'idx-static_page_info-route-url_pattern',
            'static_page_info'
        );

        $this->dropTable('static_page_info');
    }
}
