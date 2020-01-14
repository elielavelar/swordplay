<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model common\models\Zones */
/* @var $form yii\widgets\ActiveForm */
$tableName = $model->tableName();
$formName = $tableName."-form";
?>

<?php 
$form = ActiveForm::begin([
    'id'=> $formName,
]);
    Modal::begin([
        'id'=>'modal-detail',
        'header'=>'<h3 class="modal-title">Información de Zona</h3>',
        'headerOptions'=>[
            'class'=>'bg-primary',
        ],
        'footer'=> '<div class="row"> 
            <div class="col-md-12">
            <span class="pull-right">'.
                Html::submitButton('Guardar', ['class' => 'btn btn-success'])
                .Html::button('Editar', ['id'=>'btn-edit','class' => 'btn btn-primary','style'=>'display:none'])
                ."".Html::a('Cancelar', 'javascript:void(0);', ['id'=>'btn-cancel','class'=>'btn btn-danger'])
                .'</span>'
                . '</div></div>',
        'size'=> Modal::SIZE_LARGE,
        'options'=>[
            
        ],
    ]);
?>
<div class="zones-form">

    
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'Name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'Code')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'IdState')->dropDownList($model->getStates()) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'Description')->textarea(['rows' => 6]) ?>
        </div>
    </div>
    <?= $form->field($model, 'Id')->hiddenInput()->label(FALSE)->error(FALSE)?>
</div>

<?php
Modal::end();
?>
<?php ActiveForm::end(); ?>
