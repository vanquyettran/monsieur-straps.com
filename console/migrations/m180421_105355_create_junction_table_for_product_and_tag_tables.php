<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_to_tag`.
 * Has foreign keys to the tables:
 *
 * - `product`
 * - `tag`
 */
class m180421_105355_create_junction_table_for_product_and_tag_tables extends Migration
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

        $this->createTable('product_to_tag', [
            'product_id' => $this->integer(),
            'tag_id' => $this->integer(),
            'PRIMARY KEY(product_id, tag_id)',
        ], $tableOptions);

        // creates index for column `product_id`
        $this->createIndex(
            'idx-product_to_tag-product_id',
            'product_to_tag',
            'product_id'
        );

        // add foreign key for table `product`
        $this->addForeignKey(
            'fk-product_to_tag-product_id',
            'product_to_tag',
            'product_id',
            'product',
            'id',
            'CASCADE'
        );

        // creates index for column `tag_id`
        $this->createIndex(
            'idx-product_to_tag-tag_id',
            'product_to_tag',
            'tag_id'
        );

        // add foreign key for table `tag`
        $this->addForeignKey(
            'fk-product_to_tag-tag_id',
            'product_to_tag',
            'tag_id',
            'tag',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `product`
        $this->dropForeignKey(
            'fk-product_to_tag-product_id',
            'product_to_tag'
        );

        // drops index for column `product_id`
        $this->dropIndex(
            'idx-product_to_tag-product_id',
            'product_to_tag'
        );

        // drops foreign key for table `tag`
        $this->dropForeignKey(
            'fk-product_to_tag-tag_id',
            'product_to_tag'
        );

        // drops index for column `tag_id`
        $this->dropIndex(
            'idx-product_to_tag-tag_id',
            'product_to_tag'
        );

        $this->dropTable('product_to_tag');
    }
}
