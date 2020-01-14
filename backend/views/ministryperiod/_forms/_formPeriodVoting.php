<?php
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use yii\web\JsExpression;

/*@var $model backend\models\Ministryperiodvoting */
$tableName = $model->tableName();
?>
<?php $form = ActiveForm::begin([
    'id' => $formName,
]);?>
<div class="panel-body">
    <div class="row">
        <div class="col-md-4">
            <?=
            $form->field($model, 'ProcessDate')->widget(DatePicker::className(), [
                'language' => 'es',
                'readonly' => TRUE,
                'options' => ['placeholder' => 'Fecha de Proceso...'
                    ,'id' => $tableName.'-processdate'],
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
        <div class="col-md-6">
            <?= $form->field($model, 'IdState')->dropDownList($model->getStates(),['id' => $tableName.'-idstate']);?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'TotalVotingBallot')->input('number',['id' => $tableName.'-totalvotingballot']);?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'Description')->textarea(['id' => $tableName.'-description']);?>
        </div>
    </div>
</div>
<?= $form->field($model, 'Id')->hiddenInput(['id' => $tableName.'-id'])->label(false);?>
<?= $form->field($model, 'IdMinistryPeriod')->hiddenInput(['id' => $tableName.'-idministryperiod'])->label(false);?>
<?php ActiveForm::end(); ?>