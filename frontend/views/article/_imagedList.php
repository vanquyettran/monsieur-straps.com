<?php
/**
 * @var $this \yii\web\View
 * @var $models frontend\models\Article[]
 * @var $img_size string
 */


/**
 * @param $model frontend\models\Article
 * @param $img_size string
 */
$printImagedItem = function ($model, $img_size) {
    ?>
    <li>
        <?= $model->viewAnchor(
            '<div class="image">'
            . '<span>' . $model->avatarImg($img_size) . '</span>'
            . '</div>'
            . '<h3 class="name">' . $model->name . '</h3>'
            . '<div class="date">' . (new \DateTime($model->published_time))->format('d/m/Y') . '</div>'
            . '<div class="desc">' . $model->description . '</div>',
            ['class' => 'clr']
        ) ?>
    </li>
    <?php
};


/**
 * @param $model frontend\models\Article
 */
$printSimpleItem = function ($model) {
    ?>
    <li class="extra">
        <?= $model->viewAnchor() ?>
    </li>
    <?php
};


$total = count($models);

for ($i = 0; $i < $total; $i++) {
    if ($i < 5) {
        $printImagedItem($models[$i], $img_size);
    } else {
        $printSimpleItem($models[$i]);
    }
}

