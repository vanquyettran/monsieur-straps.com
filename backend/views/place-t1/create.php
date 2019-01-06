<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\PlaceT1 */

$this->title = 'Create Place T1';
$this->params['breadcrumbs'][] = ['label' => 'Place T1s', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="place-t1-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
