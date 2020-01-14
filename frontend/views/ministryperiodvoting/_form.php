<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Ministryperiodvoting */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ministryperiodvoting-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'IdMinistryPeriod')->textInput() ?>

    <?= $form->field($model, 'IdState')->textInput() ?>

    <?= $form->field($model, 'ProcessDate')->textInput() ?>

    <?= $form->field($model, 'TotalVotingBallot')->textInput() ?>

    <?= $form->field($model, 'Description')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
