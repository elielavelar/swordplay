<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\select2\Select2;
use kartik\form\ActiveForm;
use kartik\form\ActiveField;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use kartik\date\DatePicker;


/* @var $this yii\web\View */
/* @var $searchModel common\models\AppointmentsSearch */
/* @var $model common\models\Appointments */
/* @var $dataProvider yii\data\ActiveDataProvider */

$dataProvider->sort = ['defaultOrder' => ['Id'=>'DESC','AppointmentDate' => 'DESC']]; 
$urlGetAppointment = \Yii::$app->getUrlManager()->createUrl('appointment/get');
$url = \Yii::$app->getUrlManager()->createUrl('appointment');
$urlServicecentre = \Yii::$app->getUrlManager()->createUrl('servicecentre');

$this->title = 'Detalle de Citas de Ciudadano';
$this->params['breadcrumbs'][] = $this->title;

$tableName = $model->tableName();
$formName = $tableName."-form";

$maxDays = $model != NULL ? $model->getMaxDays():0;

?>
<div class="appointments-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= "";#Html::a('Create Appointments', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="box appointments-form">
        <div class="row-fluid">
            <div class="col-sm-4">
                <?php $form = ActiveForm::begin([
                    'id' => $formName,
                    'class'=>$formName,
                    'fieldConfig'=>['errorOptions'=>['encode'=>false, 'class'=>'help-block']],
                ]); ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Registro de Cita</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <?= ''#$form->field($model, 'IdServiceCentre')->dropDownList($model->getServiceCentres(),['prompt'=>'--SELECCIONE DUICENTRO--']);?>
                                <?= $form->field($model, 'IdServiceCentre')->widget(Select2::className(),[
                                    'data'=>$model->getServiceCentres(),
                                    'options' => ['placeholder' => '--SELECCIONE DUICENTRO--'],
                                    'size'=>'sm',
                                    'pluginOptions'=> [
                                        'allowClear' => true,
                                    ],
                                    'pluginEvents'=>[
                                        'select2:select'=> 'function(e){getSuggestions();}'
                                    ],
                                ]);
                                ?>
                                <?= \kartik\helpers\Html::label('', "$tableName-idservicentre", 
                                        ['id'=>'lbl-message-idservicecentre'
                                            ,'class'=>'lbl-message'
                                            ,'style'=>'display:none'])
                                ?>
                            </div>
                            <div class="col-xs-12">
                                <?= $form->field($model, 'IdType')->widget(Select2::className(),[
                                    'data'=>$model->getTypes(),
                                    'options' => ['placeholder' => '--SELECCIONE TRAMITE--'],
                                    'size'=>'sm',
                                ]);
                                ?>
                            </div>
                            <div class="col-xs-12">
                                <?=
                                    $form->field($model, 'AppointmentDate')->widget(DatePicker::className(), [
                                        'language'=>'es',
                                        'readonly'=>TRUE,
                                        'options' => ['placeholder' => 'Fecha de Cita...'],
                                        'pluginOptions'=>[
                                            'format'=>'dd-mm-yyyy',
                                            'todayHighlight'=>true,
                                            'autoclose'=>true,
                                            'daysOfWeekDisabled' => [0],
                                            'endDate' => ($maxDays > 0 ? "+".$maxDays."d":NULL)
                                        ],
                                        'pluginEvents'=> [
                                            'changeDate'=> "function(e){ validateDate(); }",
                                        ],
                                    ]);
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <?=
                                    $form->field($model, 'AppointmentHour',[
                                        'addon' => [
                                            'append' => [
                                                'content' => Html::button(Html::tag('i', '', ['class'=>'fa fa-calendar']), ['class'=>'btn btn-sm','id'=>'btn-time']), 
                                                'asButton' => true
                                            ],
                                        ],
                                    ])
                                        ->textInput(['readonly'=>TRUE,]);
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="pull-right">
                                    Consulta los Requisitos para realizar tu tr&aacute;mite
                                    <?=  Html::a('Aquí','http://www.dui-sv.com/#requisitos',['target'=>'_blank']);?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <span class="pull-right">
                                    <?= Html::button('<i class="fa fa-save"></i> Registrar', ['class' => 'btn btn-success','id'=>'btnRegister']) ?>
                                    <?= Html::button('<i class="fa fa-trash-o"></i> Limpiar', ['class' => 'btn btn-default','id'=>'btnClear']) ?>
                                </span>
                            </div>
                            <?= $form->field($model,'Id')->hiddenInput()->label(FALSE)->error(FALSE);?>
                        </div>
                    </div>
                    
                </div>
                <?php ActiveForm::end(); ?>
            </div>
            <div class="col-sm-8">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'id'=>'appointment-grid',
                    'bordered'=>false,
                    'striped'=>false,
                    #'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        #'Id',
                        #'IdCitizen',
                        'AppointmentDate',
                        'AppointmentHour',
                        [
                            'attribute'=>'IdServiceCentre',
                            'content'=>function($data){
                                return $data->IdServiceCentre ? $data->idServiceCentre->Name:NULL;
                            },
                        ],
                        [
                            'attribute'=>'IdType',
                            'content'=>function($data){
                                return $data->IdType ? $data->idType->Name:NULL;
                            },
                        ],
                        [
                            'attribute'=>'IdState',
                            'content'=>function($data){
                                return $data->IdState ? $data->idState->Name:NULL;
                            },
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{reschedule}  {cancel}',
                            'buttons'=>[
                                'reschedule'=>function ($url, $model) {
                                    return $model->reschedule ? "<a href='javascript:void(0);' val='".$model->Id."' class='reschedule' title='Reagendar Cita'>"
                                            . "<span class='glyphicon glyphicon-edit'></span></a>":"";
                                },
                                'cancel'=>function ($url, $model) {
                                    return $model->cancel ? "<a href='javascript:void(0);' val='".$model->Id."' class='app' title='Cancelar Cita'>"
                                            . "<span class='glyphicon glyphicon-remove'></span></a>":"";
                                },
                            ],
                        ],
                    ],
                    'emptyText' => 'No se encontraron resultados...',
                    'options'=>[
                        'language'=>'es',
                    ],
                ]); ?>
                <?php
                     echo "<br/><b>* Debe presentarse al Duicentro 10 minutos antes de la cita registrada</b>"
                    . "<br/><b>** De no presentarse a la cita a la hora registrada, la cita será cancelada</b>"
                    . "<br/><b>*** Debe presentarse al Duicentro seleccionado</b>"
                    . "<br/>";
                ?>
            </div>
        </div>
    </div>
</div>
<div class="modal fade in" id="modal-time" tabindex="-1" role="modal" aria-labeledby="#Label">
    <div class="modal-dialog modal-title modal-sm" role="document">
        <div class="modal-content ">
            <div class="modal-header bg-primary" >
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><a class="glyphicon glyphicon-remove" style="color: white"></a></span></button>
                <h4 class="modal-title" id="Label"><strong>Seleccionar Hora de Cita</strong></h4>
            </div>
            <div class="modal-body">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <h4>*Horarios Disponibles</h4>
                        </div>
                    </div>
                    <div class="row-fluid" id="hours"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$urlCancel = Url::to('cancel');
$reschedule = Url::to('reschedule');
$index = Url::to('index');
$script = <<< JS

$(document).ready(function(){
    
    $('#btn-time').on('click', function(){
       getAvailableTime();
    });
        
    $('#appointments-appointmenthour').on('focus', function(){
        getAvailableTime();
    });
        
    $(".app").on('click', function(){
        var id = $(this).attr("val");
        swal({
            title: "Confirmación?",
            text: "¿Está seguro que desesa cancelar la Cita?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Sí, Cancelar!",
            closeOnConfirm: false
        },
          function(){
            window.location = "$urlCancel/"+id;
          });
    });
    
    $(".reschedule").on('click', function(){
        var id = $(this).attr("val");
        swal({
            title: "Confirmación?",
            text: "¿Está seguro que desesa Reprogramar la Cita?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#FF9900",
            confirmButtonText: "Sí, Reprogramar!",
            closeOnConfirm: true
        },
          function(){
            $("#$tableName-id").val(id);
            getAppointment();
          });
    });
    
    $("#btnRegister").on('click',function(){
        swal({
            title: "Confirmación?",
            text: "¿Está seguro que desesa Registrar la Cita?",
            type: "success",
            showCancelButton: true,
            confirmButtonColor: "#007A3D",
            confirmButtonText: "Registrar",
            closeOnConfirm: true
        },
          function(){
            $("#$formName").submit();
          });
    });
    
    $("#btnClear").on("click",function(){
        window.location = "$index";
    });
});

var validateDate = function(){
    $("#$tableName-appointmenthour").val("");
    var frm = {};
    frm.ID = "$formName";
    frm.PREFIX = "$tableName-";
    frm.UPPERCASE = false;
    frm.GETBYNAME = true;
    frm.UNBOUNDNAME = true;
    frm.REPLACE = true;
    frm.REPLACESTRING = {']':''};
    frm.SEPARATORS = ["[","]"];

    var data = getValuesForm(frm);
    var params = {};
    params.URL = "$url/validatedate";
    params.DATA = {'data':JSON.stringify(data)},
    params.METHOD = 'POST';
    params.DATATYPE = 'json';
    params.SUCCESS = function(data){};
    params.ERROR = function(data){
        if(data.errors){
            $("#$tableName-idservicecentre").focus();
            frm.ERRORS = data.errors;
            setTimeout(function(){setErrorsModel(frm)},500);
        }
    };
    AjaxRequest(params);
    $("#$tableName-idservicecentre").focus();
};
        
var getAppointment = function(){
    var id = $("#$tableName-id").val();
    var data = {'id':id};
    var params = {};
    params.URL = "$urlGetAppointment";
    params.DATA = {'data':JSON.stringify(data)},
    params.METHOD = 'POST';
    params.DATATYPE = 'json';
    params.SUCCESS = function(data){
        /*$("#appointments-id").html(data.Id);
        $("#appointments-idservicecentre").val(data.IdServiceCentre);
        $("#appointments-idtype").val(data.IdType);
        $("#appointments-appointmentdate").val(data.AppointmentDate);
        */
        var frm = {};
        frm.ID = "$formName";
        frm.PREFIX = "$tableName-";
        frm.UNBOUNDNAME = true;
        frm.MATCHBYNAME = true;
        frm.SEPARATORS = ["[","]"];
        frm.DATA = data;
        setValuesForm(frm);
        $("#$tableName-appointmenthour").blur();
    };
    params.ERROR = function(data){
        swal("Error!", data.message, "error");
    };
    AjaxRequest(params);
};
        
var getAvailableTime = function(){
    var frm = {};
    frm.ID = "$formName";
    frm.PREFIX = "$tableName-";
    frm.UPPERCASE = false;
    frm.GETBYNAME = true;
    frm.UNBOUNDNAME = true;
    frm.REPLACE = true;
    frm.REPLACESTRING = {']':''};
    frm.SEPARATORS = ["[","]"];

    var data = getValuesForm(frm);
    var params = {};
    params.URL = "$url/gethours";
    params.DATA = {'data':JSON.stringify(data)},
    params.METHOD = 'POST';
    params.DATATYPE = 'json';
    params.SUCCESS = function(data){
        $("#hours").html(data.list);
        $("#modal-time").modal('show');
    };
    params.ERROR = function(data){
        /*
        if(data.code === 91001){
            frm.ERRORS = {'idservicecentre':data.message};
            setErrorsModel(frm);
        }
        */
        if(data.errors){
            frm.ERRORS = data.errors;
            setErrorsModel(frm);
        }
        
        //swal("Error!", data.message, "error");
        /*swal({
            title: "Error!",
            type: "error",
            text: data.message,
            html: true,
            showCloseButton: true,
        }, function(){
            
        });
        */
        //frm.ERRORS = data.errors;
        //setErrorsModel(data.errors);
    };
    AjaxRequest(params);
    $("#$tableName-idservicecentre").focus();
};
        
    var selectHour = function(h){
        $("#$tableName-appointmenthour").val(h).blur();
        $("#modal-time").modal("toggle");
        $("#btnRegister").focus();
    };
        
    var getSuggestions = function(){
        $('.lbl-message').empty().hide();
        var id = $("#$tableName-idservicecentre option:selected").val();
        var data = {'idservicecentre':id};
        var params = {};
        params.URL = "$urlServicecentre/getsuggestion";
        params.DATA = {'data':JSON.stringify(data)},
        params.METHOD = 'POST';
        params.DATATYPE = 'json';
        params.SUCCESS = function(data){
            if(data.suggest !== null){
                $("#lbl-message-idservicecentre").html(data.suggest).show();
            } 
        };
        params.ERROR = function(data){
            $('.lbl-message').empty().hide();
        };
        AjaxRequest(params);
    };
    var setService = function(id){
        $("#$tableName-idservicecentre").val(id).trigger("change");
        $(".lbl-message").empty().hide();
    };
JS;

$this->registerJs($script, \yii\web\VIEW::POS_END);

$js = <<< EXTJS
    
EXTJS;
$this->registerJs($js, \yii\web\VIEW::POS_END);
?>
