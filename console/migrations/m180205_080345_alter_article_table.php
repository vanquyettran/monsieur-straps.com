<?php

use yii\db\Migration;

class m180205_080345_alter_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->dropColumn('article', 'menu_item_label');
        $this->addColumn('article', 'video_src', $this->string(511)->after('description'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('article', 'video_src');
        $this->addColumn('article', 'menu_item_label', $this->string()->after('meta_description'));
    }
}
