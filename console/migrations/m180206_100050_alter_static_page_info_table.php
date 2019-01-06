<?php

use yii\db\Migration;

class m180206_100050_alter_static_page_info_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->dropColumn('static_page_info', 'menu_item_label');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->addColumn('static_page_info', 'menu_item_label', $this->string()->after('meta_description'));
    }
}
