<?php

use yii\helpers\Html;
use kartik\password\PasswordInput;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use common\models\Profile;
use common\models\State;
use common\models\Servicecentres;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
$options = ['autocomplete' => 'off'];
if(!$model->isNewRecord){
    $options['readonly']='readonly';
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
            <?= $form->field($model, 'FirstName')->textInput(['autocomplete'=>'off']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'SecondName')->textInput(['autocomplete'=>'off']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'LastName')->textInput(['autocomplete'=>'off']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'SecondLastName')->textInput(['autocomplete'=>'off']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'DisplayName')->textInput(['autocomplete'=>'off']) ?>
        </div>
        <div class="col-md-6">
            <?=$form->field($model, 'Email')->textInput(['autocomplete'=>'off'])?>
        </div>
    </div>
    <div class="row">
        
        <div class="col-md-6">
            <?= $form->field($model, 'IdServiceCentre')->widget(Select2::className(),[
                            'data'=>$model->getServicecentres(),
                            #'disabled'=> (!$model->isNewRecord),
                            'initValueText'=> ($model->IdServiceCentre ? $model->serviceCentre->Name:""),
                            'options' => ['placeholder' => '--Seleccione Departamento--'],
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
        <div class="col-md-6">
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
        <div class="col-md-4">
            <?=$form->field($model, 'CodEmployee')->textInput(['autocomplete'=>'off'])?>
        </div>
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
                <label class="control-label" for="btn-getRandomPass" style="display: block">&nbsp;</label>
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
$valid = <<< JS
        $("#user-firstname, #user-lastname").on('change', function(){
            var _enabled = $('#user-username').not(['readonly']);
            var _fname = jQuery.trim($("#user-firstname").val());
            var _arrayName = _fname.split('');
            var _lname = jQuery.trim($("#user-lastname").val());
            var _username = _lname.toUpperCase() + (_arrayName.length > 0 ? _arrayName[0]:'').toUpperCase();
            var _display =  _fname.toUpperCase()+ ' ' + _lname.toUpperCase();
            $("#user-username").val(_username);
            $("#user-displayname").val(jQuery.trim(_display));
        });
JS;
$validation = $model->isNewRecord ? $valid:'';
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
        
        $validation
    });
JS;
$this->registerJs($script, yii\web\View::POS_READY);
?>
