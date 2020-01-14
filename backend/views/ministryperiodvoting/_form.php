<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\web\JsExpression;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\Ministryperiodvoting */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$form = ActiveForm::begin([
            'id' => $formName
        ]);
?>
<div class="panel-body">
    <div class="row">
        <div class="col-md-3">
            <?=
            $form->field($model, 'IdMinistryPeriod')->widget(Select2::class, [
                'size' => Select2::MEDIUM,
                'initValueText' => ($model->IdMinistryPeriod ? $model->ministryPeriod->Name : ''),
                'data' => $model->getMinistryPeriods(),
                'options' => [
                    'placeholder' => '--PERIODO--',
                ],
                'pluginEvents' => [
                    'change' => new JsExpression('function(){}'),
                ],
            ])
            ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'IdState')->dropDownList($model->getStates(), []) ?>
        </div>
        <div class="col-md-3">
            <?=
            $form->field($model, 'ProcessDate')->widget(DatePicker::className(), [
                'language' => 'es',
                'readonly' => TRUE,
                'options' => ['placeholder' => 'Fecha de Proceso...'],
                'pluginOptions' => [
                    'format' => 'dd-mm-yyyy',
                    'todayHighlight' => true,
                    'autoclose' => true,
                #'daysOfWeekDisabled' => [0],
                ],
            ]);
            ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'TotalVotingBallot')->input('number', []) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'Description')->textarea(['rows' => 6]) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

