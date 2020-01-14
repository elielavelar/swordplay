<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\MemberSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="member-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'Id') ?>

    <?= $form->field($model, 'FirstName') ?>

    <?= $form->field($model, 'SecondName') ?>

    <?= $form->field($model, 'ThirdName') ?>

    <?= $form->field($model, 'FirstLastName') ?>

    <?php // echo $form->field($model, 'SecondLastName') ?>

    <?php // echo $form->field($model, 'Gender') ?>

    <?php // echo $form->field($model, 'IdServiceCentre') ?>

    <?php // echo $form->field($model, 'Code') ?>

    <?php // echo $form->field($model, 'IdState') ?>

    <?php // echo $form->field($model, 'BirthDate') ?>

    <?php // echo $form->field($model, 'ConversionDate') ?>

    <?php // echo $form->field($model, 'BaptismDate') ?>

    <?php // echo $form->field($model, 'DeceaseDate') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
