<?php
/**
 * @var $model \frontend\models\Product
 */
?>
    <span>
        <i class="icon calendar-icon"></i>
        <span><?= (new \DateTime($model->published_time))->format('d/m/Y') ?></span>
    </span>
    <span>
        <i class="icon eye-icon"></i>
        <span><?= $model->view_count ?> xem</span>
    </span>