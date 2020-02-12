<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ExtendedmodelfieldSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="extendedmodelfields-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'Id') ?>

    <?= $form->field($model, 'IdExtendedModelKey') ?>

    <?= $form->field($model, 'IdField') ?>

    <?= $form->field($model, 'CustomLabel') ?>

    <?= $form->field($model, 'Required') ?>

    <?php // echo $form->field($model, 'Sort') ?>

    <?php // echo $form->field($model, 'CssClass') ?>

    <?php // echo $form->field($model, 'ColSpan') ?>

    <?php // echo $form->field($model, 'RowSpan') ?>

    <?php // echo $form->field($model, 'Description') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
