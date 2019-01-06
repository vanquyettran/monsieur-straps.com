<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'username')->textInput(['maxlength' => true, 'readonly' => !$model->isNewRecord]) ?>

            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'type')->dropDownList($model->getTypeLabels()) ?>

            <?= $form->field($model, 'status')->dropDownList($model->getStatusLabels()) ?>

            <?= $form->field($model, 'role')->dropDownList($model->getRoleLabels()) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'password')->passwordInput(['placeholder' => $model->isNewRecord ? '' : 'Leave blank to change nothing']) ?>

            <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'gender')->dropDownList($model->getGenderLabels()) ?>

            <?= $form->field($model, 'date_of_birth')->textInput() ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
    initDatetimePicker("<?= Html::getInputId($model, 'date_of_birth') ?>", true);
</script>