<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_customization`.
 * Has foreign keys to the tables:
 *
 * - `product`
 */
class m180421_105303_create_product_customization_table extends Migration
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

        $this->createTable('product_customization', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'details' => $this->text(),
            'price' => $this->integer()->notNull(),
            'sort_order' => $this->integer()->notNull()->defaultValue(0),
            'featured' => $this->smallInteger(1)->notNull()->defaultValue(0),
            'available' => $this->smallInteger(1)->notNull()->defaultValue(0),
            'product_id' => $this->integer()->notNull(),
        ], $tableOptions);

        // creates index for column `product_id`
        $this->createIndex(
            'idx-product_customization-product_id',
            'product_customization',
            'product_id'
        );

        // add foreign key for table `product`
        $this->addForeignKey(
            'fk-product_customization-product_id',
            'product_customization',
            'product_id',
            'product',
            'id',
            'CASCADE'
        );

        $this->createIndex('idx-unique-name-product_id',
            'product_customization',
            ['name', 'product_id'],
            true
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropIndex(
            'idx-unique-name-product_id',
            'product_customization'
        );

        // drops foreign key for table `product`
        $this->dropForeignKey(
            'fk-product_customization-product_id',
            'product_customization'
        );

        // drops index for column `product_id`
        $this->dropIndex(
            'idx-product_customization-product_id',
            'product_customization'
        );

        $this->dropTable('product_customization');
    }
}
