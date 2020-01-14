<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\MinistriesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ministries-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'Id') ?>

    <?= $form->field($model, 'Name') ?>

    <?= $form->field($model, 'Code') ?>

    <?= $form->field($model, 'IdState') ?>

    <?= $form->field($model, 'IdType') ?>

    <?php // echo $form->field($model, 'IdValidityType') ?>

    <?php // echo $form->field($model, 'Description') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
