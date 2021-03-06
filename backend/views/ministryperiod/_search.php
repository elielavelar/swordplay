<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\MinistryperiodsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ministryperiods-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'Id') ?>

    <?= $form->field($model, 'IdMinistryServiceCentre') ?>

    <?= $form->field($model, 'IdType') ?>

    <?= $form->field($model, 'IdState') ?>

    <?= $form->field($model, 'StartDate') ?>

    <?php // echo $form->field($model, 'EndDate') ?>

    <?php // echo $form->field($model, 'Description') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
