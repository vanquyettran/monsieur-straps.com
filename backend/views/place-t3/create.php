<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\PlaceT3 */

$this->title = 'Create Place T3';
$this->params['breadcrumbs'][] = ['label' => 'Place T3s', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="place-t3-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
