<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Options */
/* @var $form yii\widgets\ActiveForm */
$frmName = $model->tableName()."_module-form";
?>

<?php $form = ActiveForm::begin([
    'id'=>$frmName,
]); ?>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'Name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'KeyWord')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'Icon')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'IdState')->dropDownList($model->getStates(),[]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'Sort')->textInput() ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'ItemMenu')->dropDownList(['0'=>'NO','1'=>'SI']); ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'RequireAuth')->dropDownList(['0'=>'NO','1'=>'SI']); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-9">
            <?= $form->field($model, 'Url')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'IdUrlType')->dropDownList($model->getUrlTypes())?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'Description')->textarea(['rows' => 6]) ?>
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
            ]); ?>
        </div>
    </div>
    <?= $form->field($model, 'Id')->hiddenInput()->label(FALSE); ?>
    <?= $form->field($model, 'IdType')->hiddenInput()->label(FALSE); ?>
    <?= $form->field($model, 'IdParent')->hiddenInput()->label(FALSE); ?>
<?php ActiveForm::end(); ?>

