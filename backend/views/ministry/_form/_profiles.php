<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use backend\models\Ministries;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model Ministries */
/* @var $modelDetail backend\models\Ministryprofiles */
/* @var $searchModel backend\models\MinistryprofilesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$url = \Yii::$app->getUrlManager()->createUrl('ministryprofile');

$filterProfile = $modelDetail->getProfiles();
$filterState = $modelDetail->getStates();

$template = "{update}&nbsp;|&nbsp;&nbsp;&nbsp;{delete} ";

$tableName = $modelDetail->tableName();
$formName = $tableName . "-form";
$modalName = "modal-profile";
$gridName = $tableName . "-grid";
?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class='row'>
            <div class="col-md-12">
                <span class="pull-right">
                    <?= Html::button('<i class="fas fa-plus-circle"></i> Agregar', ['type' => 'button', 'class' => 'btn btn-success', 'id' => 'btnAddProfile']) ?>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?=
                GridView::widget([
                    'pjax' => true,
                    'id' => $gridName,
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        #'Id',
                        [
                            'attribute' => 'Sort',
                            'width' => '10%',
                        ],
                        [
                            'attribute' => 'IdProfile',
                            'filter' => $filterProfile,
                            'filterType' => GridView::FILTER_SELECT2,
                            'filterWidgetOptions' => [
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                ],
                            ],
                            'content' => function($model) {
                                return $model->IdProfile ? $model->profile->Name : "";
                            },
                            'width' => '18%',
                            'contentOptions' => [
                                'style' => 'font-size:12px',
                            ]
                        ],
                        'CustomName',
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
                ]);
                ?>
            </div>
        </div>
    </div>
</div>
<?=
$this->render('_modalProfile', [
    'model' => $model, 'modelDetail' => $modelDetail, 'formName' => $formName, 'modalName' => $modalName,
])
?>
<?php
$script = <<< JS
   $(document).ready(function(){
       
        $('#$modalName').on('hidden.bs.modal', function () {
            clearModal();
        });
        
        $('#btnProfSave').on('click', function(){
            swal({
                title: "Confirmación?",
                text: "¿Está seguro que desesa Relacionar este Cargo al Ministerio $model->Name?",
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
        
        $("#btnAddProfile").on('click', function(){
            $("#$modalName").modal();
        });
        
        $("#btnProfCancel").on('click', function(){
            $("#$modalName").modal('toggle');
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
                        $("#$tableName-idprofile").trigger('change');
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
$this->registerJs($script, $this::POS_READY);

$js = <<< JS
    
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
                $("#$tableName-idprofile").trigger('change');
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
            text: "¿Está seguro que desesa Eliminar este Cargo del Ministerio $model->Name?",
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
        $.extend(defaultvalues,{'$tableName-idministry':$model->Id,'$tableName-sort': $modelDetail->Sort});
        frm.DEFAULTS = defaultvalues;
        frm.EXTRA = function(){
            $("#$tableName-idprofile").val(null).trigger('change');
        };
        clearForm(frm);
    };
    
    var refreshGrid = function(){
        $.pjax.reload({container:'#$gridName-pjax'});
    };
JS;
$this->registerJs($js, $this::POS_HEAD);
?>