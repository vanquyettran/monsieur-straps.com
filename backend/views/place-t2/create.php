<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\PlaceT2 */

$this->title = 'Create Place T2';
$this->params['breadcrumbs'][] = ['label' => 'Place T2s', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="place-t2-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
