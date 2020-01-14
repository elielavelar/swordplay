<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use common\models\Countries;
use common\models\State;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model common\models\Servicecentres */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="servicecentres-form">
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'Name')->textInput(['maxlength' => true,'autocomplete'=>'off']) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'MBCode')->textInput(['autocomplete'=>'off']) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'IdState')->dropDownList($model->getStates()) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'IdType')->dropDownList($model->getTypes()) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <?= $form->field($model, 'IdCountry')->widget(Select2::className(),[
                                'data'=>$model->getCountries(),
                                'disabled'=> (!$model->isNewRecord),
                                'initValueText'=> ($model->IdCountry ? $model->country->Name:""),
                                'options' => ['placeholder' => '--Seleccione País--'],
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
            <div class="col-md-4">
                <?= $form->field($model, 'IdZone')->dropDownList($model->getZones(),[]); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'Phone')->textInput(['maxlength' => true, 'autocomplete'=>'off']) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'Address')->textarea(['rows' => 6]) ?>
            </div>
        </div>
        
            
    </div>
</div>
<?php 
$script = <<< JS
    var clearName = function(){
        $("#servicecentres-idcountry").val("");
        $("#servicecentres-namecountry").val("");
    };
        
    $(document).ready(function(){
        $("#servicecentres-namecountry").on('focus',function(){
            clearName();
        });
    });
JS;
$this->registerJs($script, yii\web\View::POS_READY);
?>