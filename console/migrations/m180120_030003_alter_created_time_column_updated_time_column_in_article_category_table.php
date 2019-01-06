<?php

use yii\db\Migration;

/**
 * Handles altering status column in table `article_category`.
 */
class m180120_030003_alter_created_time_column_updated_time_column_in_article_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->alterColumn('article_category', 'created_time', $this->dateTime()->notNull());
        $this->alterColumn('article_category', 'updated_time', $this->dateTime()->notNull());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->alterColumn('article_category', 'created_time', $this->integer()->notNull());
        $this->alterColumn('article_category', 'updated_time', $this->integer()->notNull());
    }
}
