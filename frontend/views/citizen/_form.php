<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model frontend\models\Citizen */
/* @var $form yii\widgets\ActiveForm */
$url = Yii::$app->urlManager->createUrl('citizen/mail');
$urlCitizen = Yii::$app->urlManager->createUrl('citizen');
$edit = in_array(Yii::$app->controller->action->id ,["profile","view"]);
$urlMain = Url::home();
$update = in_array(Yii::$app->controller->action->id ,["update"]);
?>

<div class="citizen-form">
    <div class="panel panel-default">
        <div class="panel-body">

            <?php $form = ActiveForm::begin([
                'id'=>'citizen-form',
            ]); ?>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'Name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'LastName')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'Email')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'CreateDate')->textInput(['disabled'=>true]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'UpdateDate')->textInput(['disabled'=>true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?= Html::label('Estado', 'StateName');?>
                    <?=  Html::input('text', 'StateName', $model->IdState ? $model->idState->Name:'', ['id'=>'StateName','disabled'=>'disabled','class'=>'form-control'])?>
                </div>
            </div>
            <?php if($model->idState->Code == 'INA' && Yii::$app->controller->action->id == 'profile'){?>
            <div class="bs-callout bs-callout-danger">
                <div class="row">
                    <div class="col-md-12">
                        <p>
                            Se ha enviado un código de confirmación al correo electr&oacute;nico proporcionado.
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 field-shortcode">
                        <?= Html::label('Código Confirmación', 'shortcode');?>
                        <?= Html::textInput('shortcode',NULL,['id'=>'shortcode','class'=>'form-control input-lg']);?>
                        <div class="help-block"></div>
                    </div>
                    <div class="col-md-4">
                        <?= Html::button('Confirmar', ['id'=>'btnConfirmar','class'=>'btn btn-success btn-lg'])?>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
        <div class="panel-footer">
            <div class="form-group">
                <?php
                if($edit){
                    echo Html::a('Editar Información', ['update'], ['class' => 'btn btn-primary']) ;
                }
                if(Yii::$app->controller->action->id =="update"){
                    echo Html::a('Actualizar', 'javascript:void(0);', ['class' => 'btn btn-primary','id'=>'btnSave']) ;
                }
                ?>
                <?= Html::a('Cerrar', ['site/'], ['class' => 'btn btn-danger']) ?>
                <?php if($model->idState->Code == 'INA' && Yii::$app->controller->action->id == 'profile'){?>
                <div class="pull-right">
                    <?= Html::button('Reenviar Correo de Confirmación', ['id'=>'btn-sendMail','class' => 'btn btn-info']) ?>
                </div>
                <div class="panel panel-info" style="margin-top: 10px">
                    <div class="panel-body">
                        <h3>Pasos siguientes:</h3>
                        <ol>
                            <li>Confirmar su usuario por medio del link que recibi&oacute; 
                                en el correo electr&oacute;nico: <b><?=$model->Email?></b>
                            </li>
                            <li>Ingrese nuevamente a <b><?= Html::a(Url::to('citas.'.Yii::$app->params['mainSiteUrl']['name']))?></b></li>
                            <li>Abrir sesi&oacute;n con su correo electr&oacute;nico y contraseña.</li>
                            <li>Seleccione Duicentro, Tipo de Tr&aacute;mite, Fecha y Hora preferidas</li>
                            <li>Click en el bot&oacute;n Registrar para Guardar la Cita</li>
                            <li>Recibir&aacute; un Correo Electr&oacute;nico con su C&oacute;digo de Confirmaci&oacute;n</li>
                        </ol>
                        <h3 class="text-danger">Algunos servicios de correo electrónico pueden clasificar el correo recibido como SPAM.</h3>
                        <p>Si no encuentra el correo de confirmación en la bandeja de entrada, revise la carpeta de correo no deseado.</p>
                        <p>Revise que su direcci&oacute;n de correo electr&oacute;nico sea v&aacute;lida</p>
                    </div>
                </div>
                <?php }?>
            </div>
        </div>  
    </div>  
    <?php ActiveForm::end(); ?>
</div>
<?php
$script =  <<< JS
    $(document).ready(function(){
        
        $('#btn-sendMail').on('click',function(){
            swal({
                title: "Confirmación?",
                text: "¿Está seguro que desesa reenviar el Correo de Confirmación?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#FF9900",
                confirmButtonText: "Sí, Enviar!",
                closeOnConfirm: false
            },
              function(){
                window.location = "$url";
              });
        });
        
        $("#btnSave").on('click',function(){
            swal({
                title: "Confirmación?",
                text: "¿Está seguro que desesa actualizar la información?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#FF9900",
                confirmButtonText: "Sí, Actualizar!",
                closeOnConfirm: false
            },
              function(){
                    $("#citizen-form").submit();
              });
        });
        
        $("#btnConfirmar").on('click', function(){
            confirmUser();
        });
   });
    
   var confirmUser = function(){
        var code = jQuery.trim($("#shortcode").val());
        if(code !== ''){
            var data = {'ShortCode':code};
            var params = {};
            params.URL = "$urlCitizen/confirm";
            params.DATA = {'data':JSON.stringify(data)},
            params.METHOD = 'POST';
            params.DATATYPE = 'json';
            params.SUCCESS = function(data){
                swal({
                    title: "Usuario Confirmado",
                    type: "success",
                    text: data.message,
                    html: true,
                    showCloseButton: true,
                }, function(){
                    window.location = "$urlMain";
                });
            };
            params.ERROR = function(data){
                swal({
                    title: "Error!",
                    type: "error",
                    text: data.message,
                    html: true,
                    showCloseButton: true,
                }, function(){

                });
            };
            AjaxRequest(params);
        } else {
            var errors = {};
            errors.ID = 'shortCode';
            errors.ERRORS = {'shortcode':'Código no puede estar vacío'};
        console.log(errors);
            setErrorsModel(errors);
        }
   }; 
JS;
$this->registerJs($script);
?>