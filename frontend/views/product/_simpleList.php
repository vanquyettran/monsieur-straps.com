<?php
/**
 * @var $models \frontend\models\Product[]
 */
use yii\helpers\Html;

    foreach ($models as $model) {
        echo $model->viewAnchor(
            '<i class="icon small-brand-icon"></i>'
            . '<div class="name">'
            . Html::encode($model->name)
            . '</div>'
            . '<div class="time">'
            . (new \DateTime($model->published_time))->format('d/m/Y')
            . '</div>'
        );
    }
