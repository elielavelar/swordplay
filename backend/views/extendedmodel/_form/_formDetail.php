<?php
use kartik\form\ActiveForm;
use kartik\widgets\Select2;

/*@var $model common\models\Extendedmodelkeys */
/*@var $parentModel common\models\Extendedmodels */
?>
<?php $form = ActiveForm::begin([
    'id' => $formName,
]); ?>
<?= $form->field($model, 'IdExtendedModel')->hiddenInput()->label(false);?>
<?= $form->field($model, 'Id')->hiddenInput()->label(false);?>
<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'AttributeKeyName')->widget(Select2::className(),[
                    'data'=>$parentModel->getModelAttributes(),
                    #'disabled'=> (!$model->isNewRecord),
                    'initValueText'=> (($model->AttributeKeyName && $model->IdExtendedModel ) ? $model->extendedModel->getModelAttributeLabel($model->AttributeKeyName):""),
                    'options' => ['placeholder' => '--Seleccione Atributo--'],
                    'size'=> Select2::SIZE_MEDIUM,
                    'pluginOptions'=> [
                        'allowClear' => true,
                    ],
                'pluginEvents'=> [
                    'change'=> "function(){ }",
                ],
            ]);
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'AttributeKeyValue')->textInput(['maxlength' => true])?>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <?= $form->field($model, 'IdState')->dropDownList($model->getStates(), [])?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'Description')->textarea(['rows' => 4, 'maxlength' => true])?>
    </div>
</div>
<?php ActiveForm::end() ?>