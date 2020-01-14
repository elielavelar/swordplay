<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Options */
/* @var $form yii\widgets\ActiveForm */
$frmName = $model->tableName()."_group-form";
$fieldPrefix = 'group-'.$model->tableName()."-";
?>

<?php $form = ActiveForm::begin([
    'id'=>$frmName,
]); ?>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'Name')->textInput(['maxlength' => true,'id'=>$fieldPrefix.'name']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'KeyWord')->textInput(['maxlength' => true,'id'=>$fieldPrefix.'keyword']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'Icon')->textInput(['maxlength' => true,'id'=>$fieldPrefix.'icon']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'IdState')->dropDownList($model->getStates(),['id'=>$fieldPrefix.'idstate']) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'Sort')->textInput(['id'=>$fieldPrefix.'sort']) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'ItemMenu')->dropDownList(['0'=>'NO','1'=>'SI'],['id'=>$fieldPrefix.'itemmenu']); ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'RequireAuth')->dropDownList(['0'=>'NO','1'=>'SI'],['id'=>$fieldPrefix.'requireauth']); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-9">
            <?= $form->field($model, 'Url')->textInput(['maxlength' => true,'id'=>$fieldPrefix.'url']) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'IdUrlType')->dropDownList($model->getUrlTypes(),['id'=>$fieldPrefix.'idurltype'])?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'Description')->textarea(['rows' => 6,'id'=>$fieldPrefix.'description']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'Optionenvironment')->checkboxList($model->getEnvTypes(),[
                'class'=>'form-control checkbox-list',
                'tag'=>"span",
                'item'=> function($index, $label, $name, $checked, $value){
                    return Html::checkbox($name, $checked, [
                        'value' => $value,
                        'label' => $label,
                        'class' => 'checkbox-list',
                     ]);
                },
                'id'=>$fieldPrefix.'Optionenvironment'
            ]); ?>
        </div>
    </div>
    <?= $form->field($model, 'Id')->hiddenInput(['id'=>$fieldPrefix.'id'])->label(FALSE); ?>
    <?= $form->field($model, 'IdType')->hiddenInput(['id'=>$fieldPrefix.'idtype'])->label(FALSE); ?>
    <?= $form->field($model, 'IdParent')->hiddenInput(['id'=>$fieldPrefix.'idparent'])->label(FALSE); ?>
<?php ActiveForm::end(); ?>