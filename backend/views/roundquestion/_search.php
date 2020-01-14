<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\RoundquestionsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="roundquestions-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'Id') ?>

    <?= $form->field($model, 'Name') ?>

    <?= $form->field($model, 'IdRound') ?>

    <?= $form->field($model, 'IdType') ?>

    <?= $form->field($model, 'IdState') ?>

    <?php // echo $form->field($model, 'QuoteReference') ?>

    <?php // echo $form->field($model, 'Description') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
