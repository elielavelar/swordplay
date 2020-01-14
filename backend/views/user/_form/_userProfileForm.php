<?php

use yii\helpers\Html;
use kartik\password\PasswordInput;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */

$options = [
    'maxlength'=>TRUE,
    'disabled'=>$model->disabled,
];
$addoptions = [];
if(!$model->isNewRecord && !$model->disabled){
    $addoptions['disabled']= TRUE;
}
$addoptions = array_merge($options, $addoptions);

$verdictTitles = [
    0 => 'No Ingresada',
    1 => 'Muy Débil',
    2 => 'Débil',
    3 => 'Aceptable', 
    4 => 'Buena',
    5 => 'Excelente'
];

?>
<div class="panel-body">
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'Username')->textInput($addoptions) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'FirstName')->textInput($addoptions) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'SecondName')->textInput($addoptions) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'LastName')->textInput($addoptions) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'SecondLastName')->textInput($addoptions) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'Email')->textInput($addoptions) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'CodEmployee')->textInput($addoptions) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'serviceCentreName')->textInput($addoptions) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'profileName')->textInput($addoptions)?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'stateName')->textInput($addoptions)?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'PasswordExpirationDate')->textInput($addoptions)?>
        </div>
    </div>
    <div class="row">
        <h4>Cambiar Contrase&ntilde;a</h4>
        <div class="col-md-3">
            <div class="form-group">
                <label class="control-label" for="btn-getRandomPass" style="display: block">&nbsp;</label>
                <?= Html::button('<i class="glyphicon glyphicon-refresh"></i> Generar Contrase&ntilde;a', ['class'=>'btn control-input','id'=>'btn-getRandomPass'])?>
            </div>
        </div>
        <div class="col-md-4">
            <?=$form->field($model, '_password')->widget(PasswordInput::className(), [
                'language'=>'es_SV',
                'pluginOptions'=>[
                    'showMeter'=>TRUE,
                    'verdictTitles'=> $verdictTitles,
                ],
            ]);?>
        </div>
        <div class="col-md-4">
            <?=$form->field($model, '_passwordconfirm')->widget(PasswordInput::className(), [
                'language'=>'es_SV',
                'pluginOptions'=>[
                    'showMeter'=>FALSE,
                    'toggleMask'=>FALSE,
                ],
            ]);?>
        </div>
    </div>
</div>
<div class="panel-footer">
    <div class="row">
        <div class="col-md-12">
            <span class="pull-right">
                <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                <?= Html::a('Cancelar', '@web/', ['class'=>'btn btn-danger'])?>
            </span>
        </div>
    </div>
</div>