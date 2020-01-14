<?php
use kartik\form\ActiveForm;
use kartik\widgets\Select2;

/*@var $model backend\models\Ministryprofiles */
?>
<?php $form = ActiveForm::begin([
    'id' => $formName,
]); ?>
<?= $form->field($model, 'IdMinistry')->hiddenInput()->label(false);?>
<?= $form->field($model, 'Id')->hiddenInput()->label(false);?>
<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'IdProfile')->widget(Select2::className(),[
                    'data'=>$model->getProfiles(),
                    #'disabled'=> (!$model->isNewRecord),
                    'initValueText'=> ($model->IdProfile ? $model->profile->Name:""),
                    'options' => ['placeholder' => '--Seleccione Perfil--'],
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
        <?= $form->field($model, 'CustomName')->textInput(['maxlength' => true])?>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <?= $form->field($model, 'IdState')->dropDownList($model->getStates(), [])?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'Sort')->input('number', [])?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'Description')->textarea(['rows' => 4, 'maxlength' => true])?>
    </div>
</div>
<?php ActiveForm::end() ?>