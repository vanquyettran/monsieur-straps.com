<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu_item`.
 * Has foreign keys to the tables:
 *
 * - `article_category`
 * - `article`
 * - `menu_item`
 */
class m180205_040403_create_menu_item_table extends Migration
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

        $this->createTable('menu_item', [
            'id' => $this->primaryKey(),
            'menu_id' => $this->integer()->notNull(),
            'label' => $this->string()->notNull(),
            'sort_order' => $this->integer()->notNull()->defaultValue(0),
            'anchor_target' => $this->string(),
            'anchor_rel' => $this->string(),
            'link' => $this->string(511),
            'static_page_route' => $this->string(),
            'article_category_id' => $this->integer(),
            'article_id' => $this->integer(),
            'parent_id' => $this->integer(),
        ], $tableOptions);

        // creates index for column `article_category_id`
        $this->createIndex(
            'idx-menu_item-article_category_id',
            'menu_item',
            'article_category_id'
        );

        // add foreign key for table `article_category`
        $this->addForeignKey(
            'fk-menu_item-article_category_id',
            'menu_item',
            'article_category_id',
            'article_category',
            'id',
            'CASCADE'
        );

        // creates index for column `article_id`
        $this->createIndex(
            'idx-menu_item-article_id',
            'menu_item',
            'article_id'
        );

        // add foreign key for table `article`
        $this->addForeignKey(
            'fk-menu_item-article_id',
            'menu_item',
            'article_id',
            'article',
            'id',
            'CASCADE'
        );

        // creates index for column `parent_id`
        $this->createIndex(
            'idx-menu_item-parent_id',
            'menu_item',
            'parent_id'
        );

        // add foreign key for table `menu_item`
        $this->addForeignKey(
            'fk-menu_item-parent_id',
            'menu_item',
            'parent_id',
            'menu_item',
            'id',
            'RESTRICT'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `article_category`
        $this->dropForeignKey(
            'fk-menu_item-article_category_id',
            'menu_item'
        );

        // drops index for column `article_category_id`
        $this->dropIndex(
            'idx-menu_item-article_category_id',
            'menu_item'
        );

        // drops foreign key for table `article`
        $this->dropForeignKey(
            'fk-menu_item-article_id',
            'menu_item'
        );

        // drops index for column `article_id`
        $this->dropIndex(
            'idx-menu_item-article_id',
            'menu_item'
        );

        // drops foreign key for table `menu_item`
        $this->dropForeignKey(
            'fk-menu_item-parent_id',
            'menu_item'
        );

        // drops index for column `parent_id`
        $this->dropIndex(
            'idx-menu_item-parent_id',
            'menu_item'
        );

        $this->dropTable('menu_item');
    }
}
