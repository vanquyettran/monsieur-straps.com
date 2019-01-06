<?php

use yii\db\Migration;

/**
 * Handles altering status column in table `article`.
 */
class m180120_030002_alter_created_time_column_updated_time_column_published_time_column_in_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->alterColumn('article', 'created_time', $this->dateTime()->notNull());
        $this->alterColumn('article', 'updated_time', $this->dateTime()->notNull());
        $this->alterColumn('article', 'published_time', $this->dateTime()->notNull());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->alterColumn('article', 'created_time', $this->integer()->notNull());
        $this->alterColumn('article', 'updated_time', $this->integer()->notNull());
        $this->alterColumn('article', 'published_time', $this->integer()->notNull());
    }
}
