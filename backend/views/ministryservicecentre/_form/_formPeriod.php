<?php

use kartik\form\ActiveForm;
use kartik\widgets\Select2;
use kartik\date\DatePicker;

/* @var $model backend\models\Ministryperiods */
?>
<?php
$form = ActiveForm::begin([
            'id' => $formName,
        ]);
?>
        <?= $form->field($model, 'IdMinistryServiceCentre')->hiddenInput()->label(false); ?>
        <?= $form->field($model, 'Id')->hiddenInput()->label(false); ?>
<div class="row">
    <div class="col-md-12">
<?= $form->field($model, 'Name')->textInput(['maxlength' => true]);
?>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <?= $form->field($model, 'IdState')->dropDownList($model->getStates(), []) ?>
    </div>
    <div class="col-md-4">
        <?=
        $form->field($model, 'StartDate')->widget(DatePicker::className(), [
            'language' => 'es',
            'readonly' => TRUE,
            'options' => ['placeholder' => 'Fecha de Inicio...'],
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'todayHighlight' => true,
                'autoclose' => true,
            #'daysOfWeekDisabled' => [0],
            ],
        ]);
        ?>
    </div>
    <div class="col-md-4">
        <?=
        $form->field($model, 'EndDate')->widget(DatePicker::className(), [
            'language' => 'es',
            'readonly' => TRUE,
            'options' => ['placeholder' => 'Fecha del Fin...'],
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'todayHighlight' => true,
                'autoclose' => true,
            #'daysOfWeekDisabled' => [0],
            ],
        ]);
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
<?= $form->field($model, 'Description')->textarea(['rows' => 4, 'maxlength' => true]) ?>
    </div>
</div>
<?php ActiveForm::end() ?>