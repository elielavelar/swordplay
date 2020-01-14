<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Ministryperiodvotingcandidates */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ministryperiodvotingcandidates-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'IdVoting')->textInput() ?>

    <?= $form->field($model, 'IdMember')->textInput() ?>

    <?= $form->field($model, 'IdState')->textInput() ?>

    <?= $form->field($model, 'Sort')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
