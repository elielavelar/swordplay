<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use yii\helpers\Html;

use common\models\State;
use common\models\Type;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use yii\grid\GridView;

#use fedemotta\datatables\DataTables;

/* @var $this yii\web\View */
/* @var $model backend\models\Settings */
/* @var $modelDetail backend\models\Settingsdetail */
/* @var $form yii\widgets\ActiveForm */
/* @var $searchDetail backend\models\SettingsdetailSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$filterState= ArrayHelper::map(State::findAll(['KeyWord'=>'Settings']), 'Id', 'Name');
$filterType= ArrayHelper::map(Type::findAll(['KeyWord'=>'Data']), 'Id', 'Name');

$urlDetail = \Yii::$app->getUrlManager()->createUrl('settingsdetail');
?>
<div class="box">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-lg-12">
                    <span class="pull-left">
                        <h4 class="panel-title">Detalle</h4>
                    </span>

                    <span class="pull-right">
                        <button type="button" id="btnAddDetail" name="btnAddDetail" class="btn btn-success">
                            <i class="fas fa-plus-circle"></i> Agregar
                        </button>
                    </span>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <?php Pjax::begin([
                    'id'=>'details',
                ]); ?>    
                <?= GridView::widget([
                'id'=>'dtgrid',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'Sort',
                    'Name',
                    'Code',
                    [
                        'attribute'=>'IdState',
                        'filter'=> Html::activeDropDownList($searchModel, 'IdState', $filterState, ['class'=>'form-control','prompt'=>'--']),
                        'content'=>function($data){
                            return $data->IdState != 0 ? $data->state->Name:NULL;
                        },
                        'enableSorting'=>TRUE  
                    ],
                    [
                        'attribute'=>'IdType',
                        'filter'=> Html::activeDropDownList($searchModel, 'IdType', $filterType, ['class'=>'form-control','prompt'=>'--']),
                        'content'=>function($data){
                            return $data->IdType != 0 ? $data->type->Name:NULL;
                        },
                        'enableSorting'=>TRUE  
                    ],
                     'Value',
                    // 'Comment:ntext',

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{edit} {delete}',
                        'buttons'=>[
                            'edit' => function ($url, $model) {
                                #$urlDetail = \Yii::$app->getUrlManager()->createUrl(['settingsdetail/delete','id'=>$model->Id]);
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', "javascript:getDetail($model->Id);", [
                                            'title' => Yii::t('app', 'lead-edit'), 
                                ]);
                            },
                            'delete' => function ($url, $model) {
                                #$urlDetail = \Yii::$app->getUrlManager()->createUrl(['settingsdetail/delete','id'=>$model->Id]);
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', "javascript:deleteDetail($model->Id);", [
                                            'title' => Yii::t('app', 'lead-delete'), 
                                ]);
                            },
                        ],
                    ],
                ],
            ]); ?>
        <?php Pjax::end(); ?></div>
        </div>
    </div>
</div>
<div class="modal fade in" id="modal-detail" tabindex="-1" role="modal" aria-labeledby="#Label">
    <div class="modal-dialog modal-title modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header bg-primary" >
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><a class="glyphicon glyphicon-remove" style="color: white"></a></span></button>
                <h3 class="modal-title" id="Label"><strong>Detalle de Parametro <div class="inline" id="OrderQuestion"></div></strong></h3>
            </div>
            <div class="modal-body">
                <?=$this->render('_detailform', ['model'=>$modelDetail])?>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12">
                        <span class="pull-right">
                            <button type="button" id="btnSaveDetail" name="btnSaveDetail" class="btn btn-success">
                                <i class="fa fa-save"></i> Guardar
                            </button>
                            <button type="button" id="btnCancelDetail" name="btnCancelDetail" class="btn btn-danger">
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
        $("#btnAddDetail").on('click',function(){
            $("#modal-detail").modal();
        });
        
        $('#modal-detail').on('hidden.bs.modal', function () {
            clearModal();
        });
        
        $("#btnCancelDetail").on('click',function(){
            $("#modal-detail").modal("toggle");
        });
        
        $("#btnSaveDetail").on('click',function(){
            $("#settingsdetail-form").submit();
        });
    
        
        $("#settingsdetail-form").on('beforeSubmit',function(){
            $.ajax({
                url: "$urlDetail/save",
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
                        $("#modal-detail").modal("toggle");
                        refreshGrid();
                    }
                    else
                    {
                        swal({html:true,title:"Error: "+data.code, text: data.message, type:"error"});
                        if(data.errors){
                            var errors = {};
                            errors.ID = "settingsdetail-form";
                            errors.PREFIX = "settingsdetail-";
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
        frm.ID = "settingsdetail-form";
        var defaultvalues = {};
        $.extend(defaultvalues,{'settingsdetail-idsetting':$model->Id});
        frm.DEFAULTS = defaultvalues;
        clearForm(frm);
    };
        
JS;
$this->registerJs($script);

$script = <<< JS
    var refreshGrid = function(){
        $.pjax.reload({container:'#details',async: false});
   };
    
    var deleteDetail = function(id){
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
            var data = {'id':id};
            params.URL = "$urlDetail/delete/"+id;
            params.DATA = {},
            params.METHOD = 'POST';
            params.DATATYPE = 'json';
            params.SUCCESS = function(data){
                swal("Registro Eliminado", data.message, "warning");
                refreshGrid();
            };
            params.ERROR = function(data){
                swal("ERROR "+data.code, data.message, "error");
            };
            AjaxRequest(params);
        });
    };
        
    var getDetail = function(id){
        var params = {};
        var data = {'id':id};
        params.URL = "$urlDetail/get/";
        params.DATA = {'data':JSON.stringify(data)},
        params.METHOD = 'POST';
        params.DATATYPE = 'json';
        params.SUCCESS = function(data){
            var frm = {};
            frm.ID = "settingsdetail-form";
            frm.PREFIX = "settingsdetail-";
            frm.UNBOUNDNAME = true;
            frm.MATCHBYNAME = true;
            frm.SEPARATORS = ["[","]"];
            frm.DATA = data;
            setValuesForm(frm);
            $("#modal-detail").modal();
        };
        params.ERROR = function(data){
            swal("ERROR "+data.code, data.message, "error");
        };
        AjaxRequest(params);
    };
JS;
$this->registerJs($script, $this::POS_HEAD);

?>