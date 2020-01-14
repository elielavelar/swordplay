<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Ministries;
use kartik\widgets\Select2;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model backend\models\Ministries */
/* @var $form yii\widgets\ActiveForm */

$tableName = $model->tableName();

$url = Yii::$app->getUrlManager()->createUrl('ministry');

$resultJS = <<< JS
    function (params){
        var values = {};
        values.term = params.term;
        values.IdPeriodType = $("#$tableName-idperiodtype").val();
        return values;
    }
JS;

?>

<?php $form = ActiveForm::begin([
    'id' => $formName
]); ?>
<div class="panel-body">
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'Name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'Code')->textInput(['maxlength' => true ]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'IdType')->dropDownList($model->getTypes(),[]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'IdValidityType')->dropDownList($model->getValidityTypes(),[]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'IdEnvironmentType')->dropDownList($model->getEnvironmentTypes(),[]) ?>
        </div>
    </div>
    <div class="row">
        <?php if(!$model->isNewRecord ? ($model->IdValidityType ? $model->validityType->Code == Ministries::TYPE_VALIDITY_ROTATIVE : false) : true ){ ?>
            <div class="col-md-3">
                <?= $form->field($model, 'IdPeriodType')->widget(Select2::class, [
                    'size' => Select2::MEDIUM,
                    'initValueText' => ($model->IdPeriodType ? $model->periodType->Name: ''),
                    'data' => $model->getPeriodTypes(),
                    'options' => [
                        'placeholder' => '--TIPO DE PERIODO--',
                    ],
                    'pluginEvents'=> [
                        'change'=> new JsExpression('function(){}'),
                    ],
                    
                ]) ?>
            </div>
        <?php } ?>
        <div class="col-md-3">
            <?= $form->field($model, 'IdState')->dropDownList($model->getStates(),[]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'Description')->textarea(['rows' => 6]) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
