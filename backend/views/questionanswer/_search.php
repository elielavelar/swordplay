<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\QuestionanswersSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="questionanswers-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'Id') ?>

    <?= $form->field($model, 'Value') ?>

    <?= $form->field($model, 'IdQuestion') ?>

    <?= $form->field($model, 'TrueValue') ?>

    <?= $form->field($model, 'Description') ?>

    <?php // echo $form->field($model, 'Sort') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
