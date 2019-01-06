<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_attribute`.
 * Has foreign keys to the tables:
 *
 * - `image`
 * - `product_attribute_group`
 */
class m180421_105250_create_product_attribute_table extends Migration
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

        $this->createTable('product_attribute', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'value' => $this->string()->notNull(),
            'sort_order' => $this->integer()->notNull()->defaultValue(0),
            'illustration_image_id' => $this->integer(),
            'product_attribute_group_id' => $this->integer()->notNull(),
        ], $tableOptions);

        // creates index for column `illustration_image_id`
        $this->createIndex(
            'idx-product_attribute-illustration_image_id',
            'product_attribute',
            'illustration_image_id'
        );

        // add foreign key for table `image`
        $this->addForeignKey(
            'fk-product_attribute-illustration_image_id',
            'product_attribute',
            'illustration_image_id',
            'image',
            'id',
            'RESTRICT'
        );

        // creates index for column `product_attribute_group_id`
        $this->createIndex(
            'idx-product_attribute-product_attribute_group_id',
            'product_attribute',
            'product_attribute_group_id'
        );

        // add foreign key for table `product_attribute_group`
        $this->addForeignKey(
            'fk-product_attribute-product_attribute_group_id',
            'product_attribute',
            'product_attribute_group_id',
            'product_attribute_group',
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
            'fk-product_attribute-illustration_image_id',
            'product_attribute'
        );

        // drops index for column `illustration_image_id`
        $this->dropIndex(
            'idx-product_attribute-illustration_image_id',
            'product_attribute'
        );

        // drops foreign key for table `product_attribute_group`
        $this->dropForeignKey(
            'fk-product_attribute-product_attribute_group_id',
            'product_attribute'
        );

        // drops index for column `product_attribute_group_id`
        $this->dropIndex(
            'idx-product_attribute-product_attribute_group_id',
            'product_attribute'
        );

        $this->dropTable('product_attribute');
    }
}
