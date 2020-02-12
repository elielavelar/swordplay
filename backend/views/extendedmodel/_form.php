<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model common\models\Extendedmodels */
/* @var $form yii\widgets\ActiveForm */
$url = Yii::$app->getUrlManager()->createUrl('extendedmodel');
$tableName = $model->tableName();

$resultJS = <<< JS
    function (params){
        return {
            q: params.term, 
            IdNameSpace: $("#$tableName-idnamespace option:selected").val()
        };
    }
JS;
?>
<?php $form = ActiveForm::begin(); ?>
<div class="panel-body">
  <div class='row'>
      <div class='col-md-12'>
          <?= $form->field($model, 'Name')->textInput(['maxlength' => true]) ?>
      </div>
  </div>
  <div class='row'>
      <div class='col-md-4'>
          <?= $form->field($model, 'IdNameSpace')->widget(Select2::className(),[
                'data'=>$model->getNameSpaces(),
                'disabled'=> (!$model->isNewRecord),
                'initValueText'=> ($model->IdNameSpace ? $model->nameSpace->Name:""),
                'options' => ['placeholder' => '--SELECCIONE ESPACIO--'],
                #'size'=>'lg',
                'pluginOptions'=> [
                    'allowClear' => true,
                ],
            'pluginEvents'=> [
                'change'=> "function(){ $('#$tableName-keyword').empty(); }",
            ],
            ])?>
      </div>
      <div class='col-md-6'>
          <?= $form->field($model, 'KeyWord')->widget(Select2::className(),[
                        'size'=> Select2::MEDIUM,
                        'disabled'=> (!$model->isNewRecord),
                        'initValueText'=> $model->KeyWord,
                        'options'=> [
                            'placeholder'=> 'Digite Nombre de Modelo...',
                        ],
                        'pluginOptions'=> [
                            'allowClear'=>TRUE,
                            'minimunInputLength'=> 2,
                            'ajax' => [
                                'url'=> "$url/getmodels",
                                'dataType'=> 'json',
                                'data'=> new JsExpression($resultJS),
                                'cache'=> TRUE,
                                'delay'=> 250,
                            ],
                            'escapeMarkup'=>new JsExpression('function(markup){ return markup; }'),
                            'templateResult'=>new JsExpression('function(model){ return model.text; }'),
                            'templateSelection'=>new JsExpression('function(model){ return model.text; }'),
                        ],
                    ]);  ?>
      </div>
      <div class='col-md-2'>
          <?= $form->field($model, 'IdState')->dropDownList($model->getStates(),[]) ?>
      </div>
  </div>
  <div class='row'>
      <div class='col-md-12'>
          <?= $form->field($model, 'Description')->textarea(['rows' => 6]) ?>
      </div>
  </div>
</div>
<div class="panel-footer">
    <div class="row">
        <div class="col-md-12">
            <span class="pull-right">
                <?= Html::submitButton('<i class="fas fa-save"></i> Guardar', ['class' => 'btn btn-success']) ?>
                <?= Html::a('<i class="fas fa-times"></i> Cancelar',['index'] , ['class' => 'btn btn-danger']) ?>
            </span>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
