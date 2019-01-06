<?php

use yii\db\Migration;

/**
 * Handles adding banner_filter_column_banner_font_src to table `article`.
 */
class m181119_043355_add_banner_filter_column_banner_font_src_column_to_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('article', 'banner_filter', $this->string(255));
        $this->addColumn('article', 'banner_font_src', $this->string(2047));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('article', 'banner_filter');
        $this->dropColumn('article', 'banner_font_src');
    }
}
