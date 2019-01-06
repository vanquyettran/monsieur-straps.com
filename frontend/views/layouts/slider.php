<?php
use common\models\Banner;

/**
 * @var $banners Banner[]
 */
$banners = Banner::find()
    ->where(['active' => 1])
    ->andWhere(['position' => Banner::POSITION_HOME_SLIDER])
    ->andWhere(['<', 'start_time', date('Y-m-d H:i:s')])
    ->andWhere(['>', 'end_time', date('Y-m-d H:i:s')])
    ->orderBy('sort_order asc')
    ->all();

if (count($banners) > 0) {
    ?>
    <div class="slider top-slider"
         data-slide-time="500"
         data-autorun-delay="3000"
         data-page-size="1"
         data-display-navigator="true"
    >
        <?php
        foreach ($banners as $item) {
            if ($image = $item->image) {
                ?>
                <a class="image aspect-ratio __3x1">
                    <?= $image->img() ?>
                </a>
                <?php
            }
        }
        ?>
    </div>
    <?php
}