<?php
use kartik\form\ActiveForm;
use kartik\widgets\Select2;

/*@var $model common\models\Extendedmodelfields */
?>
<?php $form = ActiveForm::begin([
    'id' => $formName,
]); ?>
<?= $form->field($model, 'IdExtendedModelKey')->hiddenInput()->label(false);?>
<?= $form->field($model, 'Id')->hiddenInput()->label(false);?>
<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'IdField')->widget(Select2::className(),[
                    'data'=>$model->getFields(),
                    #'disabled'=> (!$model->isNewRecord),
                    'initValueText'=> ($model->IdField ? $model->field->Name : ''),
                    'options' => ['placeholder' => '--Seleccione Campo--'],
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
        <?= $form->field($model, 'Description')->textarea(['rows' => 4, 'maxlength' => true])?>
    </div>
</div>
<?php ActiveForm::end() ?>