<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Questionanswers */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="questionanswers-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'Value')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'IdQuestion')->textInput() ?>

    <?= $form->field($model, 'TrueValue')->textInput() ?>

    <?= $form->field($model, 'Description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'Sort')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
