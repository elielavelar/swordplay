<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Fieldscatalogs */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fieldscatalogs-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'IdField')->textInput() ?>

    <?= $form->field($model, 'Name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Value')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Sort')->textInput() ?>

    <?= $form->field($model, 'IdState')->textInput() ?>

    <?= $form->field($model, 'Description')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
