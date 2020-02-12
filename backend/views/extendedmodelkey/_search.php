<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ExtendedmodelkeySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="extendedmodelkeys-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'Id') ?>

    <?= $form->field($model, 'IdExtendedModel') ?>

    <?= $form->field($model, 'AttributeKeyName') ?>

    <?= $form->field($model, 'AttributeKeyValue') ?>

    <?= $form->field($model, 'IdState') ?>

    <?php // echo $form->field($model, 'Description') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
