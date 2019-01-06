<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_contact`.
 * Has foreign keys to the tables:
 *
 * - `product`
 * - `user`
 */
class m180820_172144_create_product_contact_table extends Migration
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

        $this->createTable('product_contact', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'message' => $this->string(1023),
            'customer_name' => $this->string()->notNull(),
            'customer_phone' => $this->string()->notNull(),
            'customer_email' => $this->string(),
            'status' => $this->smallInteger()->notNull(),
            'type' => $this->smallInteger()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'updated_user_id' => $this->integer(),
            'created_time' => $this->dateTime()->notNull(),
            'updated_time' => $this->dateTime()->notNull(),
        ], $tableOptions);

        // creates index for column `product_id`
        $this->createIndex(
            'idx-product_contact-product_id',
            'product_contact',
            'product_id'
        );

        // add foreign key for table `product`
        $this->addForeignKey(
            'fk-product_contact-product_id',
            'product_contact',
            'product_id',
            'product',
            'id',
            'RESTRICT'
        );

        // creates index for column `updated_user_id`
        $this->createIndex(
            'idx-product_contact-updated_user_id',
            'product_contact',
            'updated_user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-product_contact-updated_user_id',
            'product_contact',
            'updated_user_id',
            'user',
            'id',
            'RESTRICT'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `product`
        $this->dropForeignKey(
            'fk-product_contact-product_id',
            'product_contact'
        );

        // drops index for column `product_id`
        $this->dropIndex(
            'idx-product_contact-product_id',
            'product_contact'
        );

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-product_contact-updated_user_id',
            'product_contact'
        );

        // drops index for column `updated_user_id`
        $this->dropIndex(
            'idx-product_contact-updated_user_id',
            'product_contact'
        );

        $this->dropTable('product_contact');
    }
}
