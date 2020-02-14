<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\Extendedmodelkeys */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin(); ?>
<div class="panel-body">
  <div class='row'>
      <div class='col-md-6'>
          <?= $form->field($model, 'AttributeKeyName')->widget(Select2::className(),[
                    'data'=>$model->IdExtendedModel ? $model->extendedModel->getModelAttributes() : [],
                    'disabled'=> (!$model->isNewRecord),
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
    <div class='row'>
      <div class='col-md-6'>
          <?= $form->field($model, 'AttributeKeyValue')->textInput(['maxlength' => true]) ?>
      </div>
      <div class='col-md-3'>
          <?= $form->field($model, 'IdState')->dropDownList($model->getStates(),[]) ?>
      </div>
  </div>
  <div class='row'>
      <div class='col-md-12'>
          <?= $form->field($model, 'Description')->textarea(['rows' => 4]) ?>
      </div>
  </div>
</div>
<div class="panel-footer">
    <div class="row">
        <div class="col-md-12">
            <span class="pull-right">
                <?= Html::submitButton('<i class="fas fa-save"></i> Guardar', ['class' => 'btn btn-success']) ?>
                <?= Html::a('<i class="fas fa-times"></i> Cancelar',['extendedmodel/view','id' => $model->IdExtendedModel] , ['class' => 'btn btn-danger']) ?>
            </span>
        </div>
    </div>
</div>
<?= $form->field($model, 'IdExtendedModel')->hiddenInput()->label(false) ?>
<?php ActiveForm::end(); ?>
