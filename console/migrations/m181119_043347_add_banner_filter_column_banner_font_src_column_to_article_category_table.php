<?php

use yii\db\Migration;

/**
 * Handles adding banner_filter_column_banner_font_src to table `article_category`.
 */
class m181119_043347_add_banner_filter_column_banner_font_src_column_to_article_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('article_category', 'banner_filter', $this->string(255));
        $this->addColumn('article_category', 'banner_font_src', $this->string(2047));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('article_category', 'banner_filter');
        $this->dropColumn('article_category', 'banner_font_src');
    }
}
