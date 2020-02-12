<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ExtendedmodelfieldvaluesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="extendedmodelfieldvalues-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'Id') ?>

    <?= $form->field($model, 'IdExtendedModelRecord') ?>

    <?= $form->field($model, 'IdExtendedModelField') ?>

    <?= $form->field($model, 'Value') ?>

    <?= $form->field($model, 'IdFieldCatalog') ?>

    <?php // echo $form->field($model, 'CustomValue') ?>

    <?php // echo $form->field($model, 'Description') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
