<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use backend\models\Ministryperiodvoting;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\Ministryperiods */
/* @var $modelDetail backend\models\Ministryperiodvoting */
/* @var $searchModel backend\models\MinistryperiodvotingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$url = Yii::$app->getUrlManager()->createUrl('ministryperiodvoting');

$filterState = $modelDetail->getStates();

$create = Yii::$app->customFunctions->userCan('ministryperiodvotingCreate');
$update = Yii::$app->customFunctions->userCan('ministryperiodvotingUpdate');
$delete = Yii::$app->customFunctions->userCan('ministryperiovotingDelete');
$detail = Yii::$app->customFunctions->userCan('ministryperiodvotingUpdate');
$view = Yii::$app->customFunctions->userCan('ministryperiodvotingView');

$template = "";
$template .= $view ? " {view} " : "";
$template .= $update ? " {update} " : "";
$template .= $detail ? " {detail} " : "";
$template .= $delete ? "&nbsp;|&nbsp;&nbsp;&nbsp; {delete} " : "";

$tableName = $modelDetail->tableName();
$modalName = 'modal-'.$tableName;
$formName = $tableName."-form";
$gridName = $tableName."-grid";

$name = $model->Name;

?>
<div class="panel panel-default">
    <div class="panel-body">
        <?php if($create):?>
        <div class='row'>
            <div class="col-md-12">
                <span class="pull-right">
                    <?= Html::button('<i class="fas fa-plus-circle"></i> Agregar', ['type' => 'button', 'class' => 'btn btn-success', 'id' => 'btnAddPeriod']) ?>
                </span>
            </div>
        </div>
        <?php endif; ?>
        <div class="row">
            <div class="col-md-12">
                <?=
                GridView::widget([
                    'dataProvider' => $dataProvider,
                    'id' => $gridName,
                    'pjax' => true,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'Id',
                        'ProcessDate',
                        'TotalVotingBallot',
                        [
                            'attribute' => 'IdState',
                            'filter' => $filterState,
                            'content' => function($model) {
                                return $model->IdState ? $model->state->Name : '';
                            },
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => $template,
                            'buttons' => [
                                
                                'update' => function ($url, $model) {
                                    #$urlDetail = \Yii::$app->getUrlManager()->createUrl(['ministryservicecentre/update','id'=>$model->Id]);
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', "javascript:getDetail($model->Id)", [
                                                'title' => Yii::t('app', 'lead-edit'),
                                    ]);
                                },
                                'detail' => function ($url, $model) {
                                    $urlDetail = \Yii::$app->getUrlManager()->createUrl(['ministryperiodvoting/update','id'=>$model->Id]);
                                    return Html::a('<span class="glyphicon glyphicon-th"></span>', $urlDetail, [
                                                'title' => Yii::t('app', 'lead-detail'),
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
                ]);
                ?>
            </div>
        </div>
    </div>
</div>
<?=$this->render('_modalPeriodVoting', ['modelDetail' => $modelDetail, 'modalName' => $modalName, 'formName' => $formName, ])?>
<?php
$js = <<< JS
   $(document).ready(function(){
        $("#btnAddPeriod").on('click', function(){
            $("#$modalName").modal();
        });
        
        $("#btnDetSave").on('click', function(){
            swal({
                title: "Confirmación?",
                text: "¿Está seguro que desesa Guardar este Periodo para el Ministerio $name?",
                type: "success",
                showCancelButton: true,
                confirmButtonColor: "#008d4c",
                confirmButtonText: "Guardar",
                closeOnConfirm: true
            },
            function(){
                $("#$formName").submit();
            });
        });
        
        $("#btnDetCancel").on('click', function(){
            $("#$modalName").modal('toggle');
        });
        
        $('#$modalName').on('hidden.bs.modal', function () {
            clearModal();
        });
        
        $('#$modalName').on('shown.bs.modal', function () {
            $("#$tableName-processdate").kvDatepicker('update');
        });
        
        $("#$formName").on('beforeSubmit',function(){
            var data = new FormData(document.getElementById('$formName'));
            var params = {};
            params.URL = '$url/save';
            params.DATA = data;
            params.DATATYPE = 'json';
            params.METHOD = 'POST';
            params.CACHE = false;
            params.PROCESSDATA = false;
            params.CONTENTTYPE = false;
            params.SUCCESS = function(){
                swal(data.title, data.message, "success");
                $("#$modalName").modal("toggle");
                refreshGrid();
            };
            params.ERROR = function(data){
                swal({html:true,title:"Error: "+data.code, text: data.message, type:"error"});
                if(data.errors){
                    var errors = {};
                    errors.ID = "$formName";
                    errors.PREFIX = "$tableName-";
                    errors.ERRORS = data.errors;
                    errors.EXTRA = function(){
                        
                    };
                    setErrorsModel(errors);
                }
            };
            AjaxRequest(params);
        }).on('submit', function(e){
            e.preventDefault();
        });
   });
JS;
$this->registerJs($js, $this::POS_READY);

$jsHead = <<< JS
    
    var getDetail = function(id){
        var params = {};
        var data = {'Id':id};
        params.URL = "$url/get";
        params.DATA = {'data':JSON.stringify(data)},
        params.METHOD = 'POST';
        params.DATATYPE = 'json';
        params.SUCCESS = function(data){
            var frm = {};
            frm.ID = "$formName";
            frm.PREFIX = "$tableName-";
            frm.UPPERCASE = false;
            frm.SETBYID = true;
            frm.REPLACESTRING = {'[]':'',']':''};
            frm.UNBOUNDNAME = true;
            frm.MATCHBYNAME = true;
            frm.SEPARATORS = ["[","]"];
            frm.DATA = data;
            frm.EXTRA = function(){
                
            };
            setValuesForm(frm);
            $("#$modalName").modal();
        };
        params.ERROR = function(data){
            swal("ERROR "+data.code, data.message, "error");
        };
        AjaxRequest(params);
    };
        
    var deleteDetail = function(id){
        swal({
            title: "Confirmación?",
            text: "¿Está seguro que desesa Eliminar este Periodo del Ministerio $name?",
            type: "error",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Eliminar",
            closeOnConfirm: true
        },
        function(){
            var params = {};
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
        
    var clearModal = function(){
        var frm = {};
        frm.ID = "$formName";
        var defaultvalues = {};
        $.extend(defaultvalues,{'$tableName-idministryperiod':$model->Id});
        frm.DEFAULTS = defaultvalues;
        frm.EXTRA = function(){
            $("#$tableName-processdate").kvDatepicker('update');
        };
        clearForm(frm);
    };
    
    var refreshGrid = function(){
        $.pjax.reload({container:'#$gridName-pjax'});
    };
        
JS;
$this->registerJs($jsHead, $this::POS_HEAD);

?>