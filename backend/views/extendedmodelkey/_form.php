<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Extendedmodelkeys */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin(); ?>
<div class="panel-body">
  <div class='row'>
      <div class='col-md-6'>
          <?= $form->field($model, 'IdExtendedModel')->textInput() ?>
      </div>
  </div>
  <div class='row'>
      <div class='col-md-6'>
          <?= $form->field($model, 'AttributeKeyName')->textInput(['maxlength' => true]) ?>
      </div>
  </div>
  <div class='row'>
      <div class='col-md-6'>
          <?= $form->field($model, 'AttributeKeyValue')->textInput(['maxlength' => true]) ?>
      </div>
  </div>
  <div class='row'>
      <div class='col-md-6'>
          <?= $form->field($model, 'IdState')->textInput() ?>
      </div>
  </div>
  <div class='row'>
      <div class='col-md-6'>
          <?= $form->field($model, 'Description')->textarea(['rows' => 6]) ?>
      </div>
  </div>
</div>
<div class="panel-footer">
    <div class="row">
        <div class="col-md-12">
            <span class="pull-right">
                <?= Html::submitButton('<i class=\"fas fa-save\"></i> Guardar', ['class' => 'btn btn-success']) ?>
                <?= Html::a('<i class=\"fas fa-times\"></i> Cancelar',['index'] , ['class' => 'btn btn-danger']) ?>
            </span>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
