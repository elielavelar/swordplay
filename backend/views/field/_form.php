<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Fields */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin(); ?>
<div class="panel-body">
  <div class='row'>
      <div class='col-md-6'>
          <?= $form->field($model, 'Name')->textInput(['maxlength' => true]) ?>
      </div>
  </div>
  <div class='row'>
      <div class='col-md-6'>
          <?= $form->field($model, 'KeyWord')->textInput(['maxlength' => true]) ?>
      </div>
  </div>
  <div class='row'>
      <div class='col-md-6'>
          <?= $form->field($model, 'Code')->textInput(['maxlength' => true]) ?>
      </div>
  </div>
  <div class='row'>
      <div class='col-md-6'>
          <?= $form->field($model, 'IdType')->textInput() ?>
      </div>
  </div>
  <div class='row'>
      <div class='col-md-6'>
          <?= $form->field($model, 'IdState')->textInput() ?>
      </div>
  </div>
  <div class='row'>
      <div class='col-md-6'>
          <?= $form->field($model, 'HasCatalog')->textInput() ?>
      </div>
  </div>
  <div class='row'>
      <div class='col-md-6'>
          <?= $form->field($model, 'Value')->textInput(['maxlength' => true]) ?>
      </div>
  </div>
  <div class='row'>
      <div class='col-md-6'>
          <?= $form->field($model, 'MultipleValue')->textInput() ?>
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
        <div class="col-md-6">
            <span class="pull-right">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </span>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
