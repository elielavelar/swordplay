<?php

use yii\helpers\Html;
use kartik\password\PasswordInput;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use common\models\Profiles;
use common\models\States;
#use common\models\Servicecentres;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
$options = [];
if(!$model->isNewRecord){
    $options['readonly']=TRUE;
}

$url = \Yii::$app->getUrlManager()->createUrl('user');
$tableName = 'user';
$formName = $tableName.'-form';

$verdictTitles = [
    0 => 'No Ingresada',
    1 => 'Muy Débil',
    2 => 'Débil',
    3 => 'Aceptable', 
    4 => 'Buena',
    5 => 'Excelente'
];
?>

<div class="panel-body">
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'Username')->textInput($options); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'FirstName')->textInput([]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'SecondName')->textInput([]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'LastName')->textInput([]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'SecondLastName')->textInput([]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?=$form->field($model, 'Email')->textInput()?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'IdProfile')->widget(Select2::className(),[
                            'data'=>$model->getProfiles(),
                            'disabled'=> (!$model->isNewRecord),
                            'initValueText'=> ($model->IdProfile ? $model->profile->Name:""),
                            'options' => ['placeholder' => '--Seleccione Perfil--'],
                            'size'=>'lg',
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
        <div class="col-md-4">
            <?= $form->field($model, 'IdState')->dropDownList($model->getStates(), []); ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'PasswordExpirationDate')->textInput(['disabled'=>TRUE])?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label class="control-label" for="btn-getRandomPass" style="display: block">.</label>
                <?= Html::button('<i class="glyphicon glyphicon-refresh"></i> Generar Contrase&ntilde;a', ['class'=>'btn control-input','id'=>'btn-getRandomPass'])?>
            </div>
        </div>
        <div class="col-md-4">
            <?=$form->field($model, '_password')->widget(PasswordInput::className(), [
                'language'=>'es_SV',
                'pluginOptions'=>[
                    'showMeter'=>TRUE,
                    'verdictTitles' => $verdictTitles,
                ],
            ]);?>
        </div>
        <div class="col-md-4">
            <?=$form->field($model, '_passwordconfirm')->widget(PasswordInput::className(), [
                'language'=>'es_SV',
                'pluginOptions'=>[
                    'showMeter'=>FALSE,
                    'toggleMask'=>FALSE,
                ],
            ]);?>
        </div>
    </div>
</div>

<?php 
$script = <<< JS
    var getRandomPass = function(){
        var params = {};
        params.URL = "$url/getrandompass";
        params.DATA = {},
        params.METHOD = 'POST';
        params.DATATYPE = 'json';
        params.PROCESSDATA = false;
        params.CONTENTTYPE = false;
        params.CACHE = false;
        params.SUCCESS = function(data){
            $("#$tableName-_password").val(data.password);
            $("#$tableName-_passwordconfirm").val(data.password);
        };
        params.ERROR = function(data){
            swal("ERROR", data.message, "error");
        };
        AjaxRequest(params);
    };
        
    $(document).ready(function(){
        $('#btn-getRandomPass').on('click',function(){
            getRandomPass();
        });
    });
JS;
$this->registerJs($script, yii\web\View::POS_READY);
?>
