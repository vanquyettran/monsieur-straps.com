<?php

use yii\db\Migration;

/**
 * Handles adding displaying_areas to table `article_category`.
 */
class m180206_102426_add_displaying_areas_column_to_article_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('article_category', 'displaying_areas', $this->string()->after('sort_order'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('article_category', 'displaying_areas');
    }
}
