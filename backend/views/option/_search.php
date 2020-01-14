<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\OptionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="options-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'Id') ?>

    <?= $form->field($model, 'Name') ?>

    <?= $form->field($model, 'KeyWord') ?>

    <?= $form->field($model, 'IdState') ?>

    <?= $form->field($model, 'IdType') ?>

    <?php // echo $form->field($model, 'IdParent') ?>

    <?php // echo $form->field($model, 'Icon') ?>

    <?php // echo $form->field($model, 'Url') ?>

    <?php // echo $form->field($model, 'Sort') ?>

    <?php // echo $form->field($model, 'Description') ?>

    <?php // echo $form->field($model, 'ItemMenu') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
