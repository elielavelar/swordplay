<?php
use kartik\widgets\ActiveForm;
use yii\web\JsExpression;
/*@var $model backend\models\Ministryvotingballot */

?>
<?php $form = ActiveForm::begin([
    'id' => $formName,
]);?>
<div class="panel-body">
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'Number')->input('number',['id' => $tableName."-number",'disabled' => true]);?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'IdState')->dropDownList($model->getStates(),['id' => $tableName."-idstate",'disabled' => true]);?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'userCreateName')->textInput(['id' => $tableName."-usercreatename",'disabled' => true])->label($model->getAttributeLabel('IdUserCreate')) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'userUpdateName')->textInput(['id' => $tableName."-userupdatename",'disabled' => true])->label($model->getAttributeLabel('IdUserUpdate')) ?>
        </div>
    </div>
    
</div>
<?= $form->field($model, 'Id')->hiddenInput()->label(false);?>
<?= $form->field($model, 'IdVoting')->hiddenInput()->label(false);?>
<?php ActiveForm::end(); ?>