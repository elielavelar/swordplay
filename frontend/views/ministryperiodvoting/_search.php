<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\MinistryperiodvotingSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ministryperiodvoting-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'Id') ?>

    <?= $form->field($model, 'IdMinistryPeriod') ?>

    <?= $form->field($model, 'IdState') ?>

    <?= $form->field($model, 'ProcessDate') ?>

    <?= $form->field($model, 'TotalVotingBallot') ?>

    <?php // echo $form->field($model, 'Description') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
