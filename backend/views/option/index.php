
<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \backend\models\Options* /
/* @var $modelGroup backend\models\Options*/
/* @var $modelController backend\models\Options*/
/* @var $modelAction backend\models\Options*/
/* @var $modelPermission backend\models\Options*/
/* @var $searchModel backend\models\OptionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Opciones del Sistema';
$this->params['breadcrumbs'][] = $this->title;

$url =  \Yii::$app->getUrlManager()->createUrl('option');
$tableName = $model->tableName();

$frmName = $tableName."_module-form";
$modal_name_module = 'modal-module';

$frmGroupName = $tableName."_group-form";
$modal_name_group = 'modal-group';

$frmControllerName = $tableName."_controller-form";
$modal_name_controller = 'modal-controller';

$modal_name_action = 'modal-action';
$frmActionName = $tableName."_action-form";

$modal_name_permission = 'modal-permission';
$frmPermissionName = $tableName."_permission-form";


?>
<style>
    .grid-view .action-column span {
        margin-right: 5px;
    }
    .grid-view i.fa {
        margin-right: 5px;
    }
</style>
<div class="options-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?=  Html::button('<i class="fas fa-plus-circle"></i> Agregar Módulo', ['class'=>'btn btn-success','id'=>'btnAddModule']);?>
    </p>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="grid-view">
                <table id="<?=$tableName."-details"?>" class="table table-bordered table-hover" style="width: 100%">
                    <thead>
                        <tr>
                            <th class="active" style="width: 2%"></th>
                            <th class="active" style="width: 2%"></th>
                            <th class="active" style="width: 2%"></th>
                            <th class="active" style="width: 2%"></th>
                            <th class="active" style="width: 18%">NOMBRE</th>
                            <th class="active" style="width: 9%">TIPO</th>
                            <th class="active" style="width: 24%">URL</th>
                            <th class="active" style="width: 14%">LLAVE</th>
                            <th class="active" style="width: 5%">ESTADO</th>
                            <th class="active" style="width: 4%">MENU</th>
                            <th class="active" style="width: 5%">AUTENTICACION</th>
                            <th class="active" style="width: 4%">LOG</th>
                            <th class="active" style="width: 4%">TRANSACCION</th>
                            <th class="active action-column" style="width: 8%">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
            
    </div>
</div>
<div class="modal fade in" id="<?=$modal_name_module?>" tabindex="-1" role="modal" aria-labeledby="#Label">
    <div class="modal-dialog modal-title modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header bg-primary" >
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><a class="glyphicon glyphicon-remove" style="color: white"></a></span></button>
                <h3 class="modal-title" id="Label"><strong>Detalle de Módulo</strong></h3>
            </div>
            <div class="modal-body">
                <?=$this->render('_form/_form', ['model'=>$model])?>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12">
                        <span class="pull-right">
                            <button type="button" id="btnSaveModule" name="btnSaveModule" class="btn btn-success">
                                <i class="fa fa-save"></i> Guardar
                            </button>
                            <button type="button" id="btnCancelModule" name="btnCancelModule" class="btn btn-danger">
                                <i class="fa fa-times"></i> Cancelar
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade in" id="<?=$modal_name_controller?>" tabindex="-1" role="modal" aria-labeledby="#Label">
    <div class="modal-dialog modal-title modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header bg-primary" >
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><a class="glyphicon glyphicon-remove" style="color: white"></a></span></button>
                <h3 class="modal-title" id="Label"><strong>Detalle de Controlador</strong></h3>
            </div>
            <div class="modal-body">
                <?=$this->render('_form/_formController', ['model'=>$modelController])?>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12">
                        <span class="pull-right">
                            <button type="button" id="btnSaveController" name="btnSaveController" class="btn btn-success">
                                <i class="fa fa-save"></i> Guardar
                            </button>
                            <button type="button" id="btnCancelController" name="btnCancelController" class="btn btn-danger">
                                <i class="fa fa-times"></i> Cancelar
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade in" id="<?=$modal_name_group?>" tabindex="-1" role="modal" aria-labeledby="#Label">
    <div class="modal-dialog modal-title modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header bg-primary" >
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><a class="glyphicon glyphicon-remove" style="color: white"></a></span></button>
                <h3 class="modal-title" id="Label"><strong>Detalle de Grupo</strong></h3>
            </div>
            <div class="modal-body">
                <?=$this->render('_form/_formGroup', ['model'=>$modelGroup])?>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12">
                        <span class="pull-right">
                            <button type="button" id="btnSaveGroup" name="btnSaveGroup" class="btn btn-success">
                                <i class="fa fa-save"></i> Guardar
                            </button>
                            <button type="button" id="btnCancelGroup" name="btnCancelGroup" class="btn btn-danger">
                                <i class="fa fa-times"></i> Cancelar
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade in" id="<?=$modal_name_action?>" tabindex="-1" role="modal" aria-labeledby="#Label">
    <div class="modal-dialog modal-title modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header bg-primary" >
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><a class="glyphicon glyphicon-remove" style="color: white"></a></span></button>
                <h3 class="modal-title" id="Label"><strong>Detalle de Acci&oacute;n</strong></h3>
            </div>
            <div class="modal-body">
                <?=$this->render('_form/_formAction', ['model'=>$modelAction])?>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12">
                        <span class="pull-right">
                            <button type="button" id="btnSaveAction" name="btnSaveAction" class="btn btn-success">
                                <i class="fa fa-save"></i> Guardar
                            </button>
                            <button type="button" id="btnCancelAction" name="btnCancelAction" class="btn btn-danger">
                                <i class="fa fa-times"></i> Cancelar
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade in" id="<?=$modal_name_permission?>" tabindex="-1" role="modal" aria-labeledby="#Label">
    <div class="modal-dialog modal-title modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header bg-primary" >
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><a class="glyphicon glyphicon-remove" style="color: white"></a></span></button>
                <h3 class="modal-title" id="Label"><strong>Detalle de Permiso</strong></h3>
            </div>
            <div class="modal-body">
                <?=$this->render('_form/_formPermission', ['model'=>$modelPermission])?>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12">
                        <span class="pull-right">
                            <button type="button" id="btnSavePermission" name="btnSavePermission" class="btn btn-success">
                                <i class="fa fa-save"></i> Guardar
                            </button>
                            <button type="button" id="btnCancelPermission" name="btnCancelPermission" class="btn btn-danger">
                                <i class="fa fa-times"></i> Cancelar
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php

$script = <<< JS
    $(document).ready(function(){
        
        refreshGrid();
        
        /*MODULE*/
        $("#btnAddModule").on('click',function(){
            $("#$modal_name_module").modal();
        });
        
        $('#$modal_name_module').on('hidden.bs.modal', function () {
            clearModal();
        });
        
        $("#btnCancelModule").on('click',function(){
            $("#$modal_name_module").modal("toggle");
        });
        
        $("#btnSaveModule").on('click',function(){
            $("#$frmName").submit();
        });
    
        
        $("#$frmName").on('beforeSubmit',function(){
            $.ajax({
                url: "$url/save",
                type: "POST",
                data:  new FormData(this),
                contentType: false,
                cache: false,
                processData:false,
                success: function(data)
                {		
                    var data = JSON.parse(data);
                    if(data.success == true)
                    {
                        swal(data.title, data.message, "success");
                        $("#$modal_name_module").modal("toggle");
                        refreshGrid();
                    }
                    else
                    {
                        swal({html:true,title:"Error: "+data.code, text: data.message, type:"error"});
                        if(data.errors){
                            var errors = {};
                            errors.ID = "$frmName";
                            errors.PREFIX = "$tableName-";
                            errors.ERRORS = data.errors;
                            setErrorsModel(errors);
                        }
                    }
                },
                error: function() 
                {
                    console.log(data);
                } 	        
           });
        }).on('submit', function(e){
            e.preventDefault();
        });
        
        /*
        $('#btnSaveModule').on('click', function(){

            var frm = {};
            frm.ID = "$frmName";
            frm.PREFIX = "$tableName-";
            frm.UPPERCASE = false;
            frm.GETFORM = true;
            
            var data = getValuesForm(frm);
            $("#$modal_name_module .input-group").removeClass("has-error");
            $("#$modal_name_module .lblerror").remove();
        
            var params = {};
            params.URL = "$url/save";
            params.DATA = data,
            params.METHOD = 'POST';
            params.DATATYPE = 'json';
            params.PROCESSDATA = false;
            params.CONTENTTYPE = false;
            //params.BEFORESEND = function(){};
            params.CACHE = false;
            params.SUCCESS = function(data){
                swal("Datos Guardados", data.message, "success");
                $("#$modal_name_module").modal('toggle');
                refreshGrid();
            };
            params.ERROR = function(data){
                alert(data.message);
                if(data.errors !== null){
                    var errors = {};
                    errors.ID = frm.ID;
                    errors.PREFIX = frm.PREFIX;
                    errors.ERRORS = data.errors;
                    setErrorsModel(errors);
                }
            };
            AjaxRequest(params);
        });
        */
        
        
        /*GROUP*/
        
        $('#$modal_name_group').on('hidden.bs.modal', function () {
            clearModalGroup();
        });
        
        $("#btnCancelGroup").on('click',function(){
            $("#$modal_name_group").modal("toggle");
        });
        
        $("#btnSaveGroup").on('click',function(){
            $("#$frmGroupName").submit();
        });
    
        
        $("#$frmGroupName").on('beforeSubmit',function(){
            $.ajax({
                url: "$url/save",
                type: "POST",
                data:  new FormData(this),
                contentType: false,
                cache: false,
                processData:false,
                success: function(data)
                {		
                    var data = JSON.parse(data);
                    if(data.success == true)
                    {
                        swal(data.title, data.message, "success");
                        $("#$modal_name_group").modal("toggle");
                        refreshGrid();
                    }
                    else
                    {
                        swal({html:true,title:"Error: "+data.code, text: data.message, type:"error"});
                        if(data.errors){
                            var errors = {};
                            errors.ID = "$frmGroupName";
                            errors.PREFIX = "group-$tableName-";
                            errors.ERRORS = data.errors;
                            setErrorsModel(errors);
                        }
                    }
                },
                error: function() 
                {
                    console.log(data);
                } 	        
           });
        }).on('submit', function(e){
            e.preventDefault();
        });
        
        /*CONTROLLER*/
        
        $('#$modal_name_controller').on('hidden.bs.modal', function () {
            clearModalController();
        });
        
        $("#btnCancelController").on('click',function(){
            $("#$modal_name_controller").modal("toggle");
        });
        
        $("#btnSaveController").on('click',function(){
            $("#$frmControllerName").submit();
        });
    
        
        $("#$frmControllerName").on('beforeSubmit',function(){
            $.ajax({
                url: "$url/save",
                type: "POST",
                data:  new FormData(this),
                contentType: false,
                cache: false,
                processData:false,
                success: function(data)
                {		
                    var data = JSON.parse(data);
                    if(data.success == true)
                    {
                        swal(data.title, data.message, "success");
                        $("#$modal_name_controller").modal("toggle");
                        refreshGrid();
                    }
                    else
                    {
                        swal({html:true,title:"Error: "+data.code, text: data.message, type:"error"});
                        if(data.errors){
                            var errors = {};
                            errors.ID = "$frmControllerName";
                            errors.PREFIX = "controller-$tableName-";
                            errors.ERRORS = data.errors;
                            setErrorsModel(errors);
                        }
                    }
                },
                error: function() 
                {
                    console.log(data);
                } 	        
           });
        }).on('submit', function(e){
            e.preventDefault();
        });
        
        /*ACTION*/
        
        $('#$modal_name_action').on('hidden.bs.modal', function () {
            clearModalAction();
        });
        
        $("#btnCancelAction").on('click',function(){
            $("#$modal_name_action").modal("toggle");
        });
        
        $("#btnSaveAction").on('click',function(){
            $("#$frmActionName").submit();
        });
    
        
        $("#$frmActionName").on('beforeSubmit',function(){
            $.ajax({
                url: "$url/save",
                type: "POST",
                data:  new FormData(this),
                contentType: false,
                cache: false,
                processData:false,
                success: function(data)
                {		
                    var data = JSON.parse(data);
                    if(data.success == true)
                    {
                        swal(data.title, data.message, "success");
                        $("#$modal_name_action").modal("toggle");
                        refreshGrid();
                    }
                    else
                    {
                        swal({html:true,title:"Error: "+data.code, text: data.message, type:"error"});
                        if(data.errors){
                            var errors = {};
                            errors.ID = "$frmActionName";
                            errors.PREFIX = "action-$tableName-";
                            errors.ERRORS = data.errors;
                            setErrorsModel(errors);
                        }
                    }
                },
                error: function() 
                {
                    console.log(data);
                } 	        
           });
        }).on('submit', function(e){
            e.preventDefault();
        });
        
        /*PERMISSION*/
        
        $('#$modal_name_permission').on('hidden.bs.modal', function () {
            clearModalPermission();
        });
        
        $("#btnCancelPermission").on('click',function(){
            $("#$modal_name_permission").modal("toggle");
        });
        
        $("#btnSavePermission").on('click',function(){
            $("#$frmPermissionName").submit();
        });
    
        
        $("#$frmPermissionName").on('beforeSubmit',function(){
            $.ajax({
                url: "$url/save",
                type: "POST",
                data:  new FormData(this),
                contentType: false,
                cache: false,
                processData:false,
                success: function(data)
                {		
                    var data = JSON.parse(data);
                    if(data.success == true)
                    {
                        swal(data.title, data.message, "success");
                        $("#$modal_name_permission").modal("toggle");
                        refreshGrid();
                    }
                    else
                    {
                        swal({html:true,title:"Error: "+data.code, text: data.message, type:"error"});
                        if(data.errors){
                            var errors = {};
                            errors.ID = "$frmPermissionName";
                            errors.PREFIX = "permission-$tableName-";
                            errors.ERRORS = data.errors;
                            setErrorsModel(errors);
                        }
                    }
                },
                error: function() 
                {
                    console.log(data);
                } 	        
           });
        }).on('submit', function(e){
            e.preventDefault();
        });
        
    });
        
    var clearModal = function(){
        var frm = {};
        frm.ID = "$frmName";
        var defaultvalues = {};
        $.extend(defaultvalues,{'$tableName-idtype':$model->IdType,'$tableName-requireauth':$model->RequireAuth});
        frm.DEFAULTS = defaultvalues;
        clearForm(frm);
    };
        
    var clearModalGroup = function(){
        var frm = {};
        frm.ID = "$frmGroupName";
        var defaultvalues = {};
        $.extend(defaultvalues,{'group-$tableName-idtype':$modelGroup->IdType,'group-$tableName-requireauth':$modelGroup->RequireAuth});
        frm.DEFAULTS = defaultvalues;
        clearForm(frm);
    };
        
    var clearModalController = function(){
        var frm = {};
        frm.ID = "$frmControllerName";
        var defaultvalues = {};
        $.extend(defaultvalues,{'controller-$tableName-idtype':$modelController->IdType,'controller-$tableName-requireauth':$modelController->RequireAuth});
        frm.DEFAULTS = defaultvalues;
        clearForm(frm);
    };
        
    var clearModalAction = function(){
        var frm = {};
        frm.ID = "$frmActionName";
        var defaultvalues = {};
        $.extend(defaultvalues,{'action-$tableName-idtype':$modelAction->IdType, 'action-$tableName-itemmenu':$modelAction->ItemMenu,'action-$tableName-requireauth':$modelAction->RequireAuth,'action-$tableName-savelog':$modelAction->SaveLog,'action-$tableName-savetransaction':$modelAction->SaveTransaction});
        frm.DEFAULTS = defaultvalues;
        clearForm(frm);
    };
        
    var clearModalPermission = function(){
        var frm = {};
        frm.ID = "$frmPermissionName";
        var defaultvalues = {};
        $.extend(defaultvalues,{'permission-$tableName-idtype':$modelPermission->IdType, 'permission-$tableName-itemmenu':$modelPermission->ItemMenu,'permission-$tableName-requireauth':$modelPermission->RequireAuth});
        frm.DEFAULTS = defaultvalues;
        clearForm(frm);
    };
        
JS;
$this->registerJs($script);

$script = <<< JS
        
    var addGroup = function(id){
        $("#group-$tableName-idparent").val(id);
        $("#$modal_name_group").modal();
    };
        
    var addController = function(id){
        $("#controller-$tableName-idparent").val(id);
        $("#$modal_name_controller").modal();
    };
        
    var addAction = function(id){
        $("#action-$tableName-idparent").val(id);
        $("#$modal_name_action").modal();
    };
        
    var addPermission = function(id){
        $("#permission-$tableName-idparent").val(id);
        $("#$modal_name_permission").modal();
    };
        
    var getDetails = function(){
        var params = {};
        
        params.URL = "$url/gethtmllist";
        params.DATA = {};
        var success = function(data){
            $("#options-details").find('tbody').html(data.list);
        };
        
        var params = {};
        params.URL = "$url/gethtmllist";
        params.DATA = {},
        params.METHOD = 'POST';
        params.DATATYPE = 'json';
        params.PROCESSDATA = false;
        params.CONTENTTYPE = false;
        params.CACHE = false;
        params.SUCCESS = function(data){
            $("#options-details").find('tbody').html(data.list);
        };
        params.ERROR = function(data){
            console.log(data.message);
        };
        AjaxRequest(params);
        
    };
        
    var refreshGrid = function(){
        //$.pjax.reload({container:'#$tableName-grid-pjax'});
        getDetails();
    };
    
   var deletePermission = function(id){
        swal({
            title: "Confirmación?",
            text: "¿Está seguro que desesa Eliminar este Registro?",
            type: "error",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Eliminar",
            closeOnConfirm: true
        },
        function(){
            var params = {};
            var data = {'Id':id};
            params.URL = "$url/delete/"+id;
            params.DATA = {},
            params.METHOD = 'POST';
            params.DATATYPE = 'json';
            params.SUCCESS = function(data){
                swal(data.title, data.message, "warning");
                refreshGrid();
            };
            params.ERROR = function(data){
                swal("ERROR "+data.code, data.message, "error");
            };
            AjaxRequest(params);
        });
    };
        
    var deleteModule = function(id){
        deletePermission(id);
    };
        
    var deleteGroup = function(id){
        deletePermission(id);
    };
        
    var deleteController = function(id){
        deletePermission(id);
    };
        
    var deleteAction = function(id){
        deletePermission(id);
    };
        
    var editModule = function(id){
        var params = {};
        var data = {'Id':id};
        params.URL = "$url/get/";
        params.DATA = {'data':JSON.stringify(data)},
        params.METHOD = 'POST';
        params.DATATYPE = 'json';
        params.SUCCESS = function(data){
            var frm = {};
            frm.ID = "$frmName";
            frm.PREFIX = "$tableName-";
            frm.UPPERCASE = false;
            frm.SETBYID = true;
            frm.REPLACESTRING = {'[]':'',']':''};
            frm.UNBOUNDNAME = true;
            frm.MATCHBYNAME = true;
            frm.SEPARATORS = ["[","]"];
            frm.DATA = data;
            setValuesForm(frm);
            $("#$modal_name_module").modal();
        };
        params.ERROR = function(data){
            swal("ERROR "+data.code, data.message, "error");
        };
        AjaxRequest(params);
    };
        
    var editGroup = function(id){
        var params = {};
        var data = {'Id':id};
        params.URL = "$url/get/";
        params.DATA = {'data':JSON.stringify(data)},
        params.METHOD = 'POST';
        params.DATATYPE = 'json';
        params.SUCCESS = function(data){
            var frm = {};
            frm.ID = "$frmGroupName";
            frm.PREFIX = "group-$tableName-";
            frm.UPPERCASE = false;
            frm.SETBYID = true;
            frm.REPLACESTRING = {'[]':'',']':''};
            frm.UNBOUNDNAME = true;
            frm.MATCHBYNAME = true;
            frm.SEPARATORS = ["[","]"];
            frm.DATA = data;
            setValuesForm(frm);
            $("#$modal_name_group").modal();
        };
        params.ERROR = function(data){
            swal("ERROR "+data.code, data.message, "error");
        };
        AjaxRequest(params);
    };
        
    var editController = function(id){
        var params = {};
        var data = {'Id':id};
        params.URL = "$url/get/";
        params.DATA = {'data':JSON.stringify(data)},
        params.METHOD = 'POST';
        params.DATATYPE = 'json';
        params.SUCCESS = function(data){
            var frm = {};
            frm.ID = "$frmControllerName";
            frm.PREFIX = "controller-$tableName-";
            frm.UPPERCASE = false;
            frm.SETBYID = true;
            frm.REPLACESTRING = {'[]':'',']':''};
            frm.UNBOUNDNAME = true;
            frm.MATCHBYNAME = true;
            frm.SEPARATORS = ["[","]"];
            frm.DATA = data;
            setValuesForm(frm);
            $("#$modal_name_controller").modal();
        };
        params.ERROR = function(data){
            swal("ERROR "+data.code, data.message, "error");
        };
        AjaxRequest(params);
    };
        
    var editAction = function(id){
        var params = {};
        var data = {'Id':id};
        params.URL = "$url/get/";
        params.DATA = {'data':JSON.stringify(data)},
        params.METHOD = 'POST';
        params.DATATYPE = 'json';
        params.SUCCESS = function(data){
            var frm = {};
            frm.ID = "$frmActionName";
            frm.PREFIX = "action-$tableName-";
            frm.UPPERCASE = false;
            frm.SETBYID = true;
            frm.REPLACESTRING = {'[]':'',']':''};
            frm.UNBOUNDNAME = true;
            frm.MATCHBYNAME = true;
            frm.SEPARATORS = ["[","]"];
            frm.DATA = data;
            setValuesForm(frm);
            $("#$modal_name_action").modal();
        };
        params.ERROR = function(data){
            swal("ERROR "+data.code, data.message, "error");
        };
        AjaxRequest(params);
    };
        
    var editPermission = function(id){
        var params = {};
        var data = {'Id':id};
        params.URL = "$url/get/";
        params.DATA = {'data':JSON.stringify(data)},
        params.METHOD = 'POST';
        params.DATATYPE = 'json';
        params.SUCCESS = function(data){
            var frm = {};
            frm.ID = "$frmPermissionName";
            frm.PREFIX = "permission-$tableName-";
            frm.UPPERCASE = false;
            frm.SETBYID = true;
            frm.REPLACESTRING = {'[]':'',']':''};
            frm.UNBOUNDNAME = true;
            frm.MATCHBYNAME = true;
            frm.SEPARATORS = ["["];
            frm.DATA = data;
            setValuesForm(frm);
            $("#$modal_name_permission").modal();
        };
        params.ERROR = function(data){
            swal("ERROR "+data.code, data.message, "error");
        };
        AjaxRequest(params);
    };
JS;
$this->registerJs($script, $this::POS_HEAD);

?>