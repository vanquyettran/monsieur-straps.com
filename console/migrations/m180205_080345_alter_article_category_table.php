<?php

use yii\db\Migration;

class m180205_080345_alter_article_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->dropColumn('article_category', 'menu_item_label');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->addColumn('article_category', 'menu_item_label', $this->string()->after('meta_description'));
    }
}
