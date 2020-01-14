<?php
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\helpers\Html;
use common\models\State;
use common\models\Type;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;
use common\models\Catalogdetailvalues;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/* @var $this yii\web\View */
/* @var $model \common\models\Catalogdetails */
/* @var $searchModel \backend\models\CatalogdetailvaluesSearch */
/* @var $modelDetail common\models\Catalogdetailvalues;
 */
$tableName = $modelDetail->tableName();
$frmName = 'form-'.$tableName;

$create = Yii::$app->customFunctions->userCan("catalogdetailCreate");
$view = Yii::$app->customFunctions->userCan("catalogdetailView");
$update = Yii::$app->customFunctions->userCan("catalogdetailUpdate");
$delete = Yii::$app->customFunctions->userCan("catalogdetailDelete");

$template = "";
$template .= $view ? "{view} ":"";
$template .= $update ? " {edit} ":"";
$template .= $delete ? " |   {delete} ":"";

$url = Yii::$app->getUrlManager()->createUrl('catalogdetailvalue');

?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <span class="pull-right">
                    <?= $create ? Html::button("<i class='fas fa-plus-circle'></i> Agregar Valor", ['class'=>'btn btn-success','id'=>'btnAddDetail']):""; ?>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <?php Pjax::begin([
                    'id'=>'details'
                ]); ?>    
                <?= GridView::widget([
                'id'=>'dtgrid',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'pjax'=>TRUE,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute'=>'Sort',
                        'headerOptions' => ['style' => 'width:10%'],
                    ],
                    [
                        'attribute'=>'IdDataType',
                        'filter'=> Html::activeDropDownList($searchModel, 'IdDataType', $modelDetail->getDataTypes(), ['class'=>'form-control','prompt'=>'--']),
                        'content'=>function($data){
                            return $data->IdDataType != 0 ? $data->dataType->Name:NULL;
                        },
                        'enableSorting'=>TRUE  
                    ],
                    [
                        'attribute'=>'IdValueType',
                        'filter'=> Html::activeDropDownList($searchModel, 'IdValueType', $modelDetail->getValueTypes(), ['class'=>'form-control','prompt'=>'--']),
                        'content'=>function($data){
                            return $data->IdValueType != 0 ? $data->valueType->Name:NULL;
                        },
                        'enableSorting'=>TRUE  
                    ],
                    'Value',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => $template,
                        'buttons'=>[
                            'edit' => function ($url, $model) {
                                #$urlDetail = \Yii::$app->getUrlManager()->createUrl(['settingsdetail/delete','id'=>$model->Id]);
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', "javascript:editDetail($model->Id);", [
                                            'title' => Yii::t('app', 'Editar Valor'), 
                                ]);
                            },
                            'delete' => function ($url, $model) {
                                #$urlDetail = \Yii::$app->getUrlManager()->createUrl(['settingsdetail/delete','id'=>$model->Id]);
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', "javascript:deleteDetail($model->Id);", [
                                            'title' => Yii::t('app', 'Eliminar Valor'), 
                                ]);
                            },
                        ],
                    ],
                ],
            ]); ?>
            <?php Pjax::end(); ?>
            </div>
        </div>
    </div>
</div>
<?=$this->render('_formValue', ['model'=> $modelDetail])?>
<?php
$js = <<< JS
   $(document).ready(function(){
        $("#btnAddDetail").on('click',function(){
            $("#modal-$tableName").modal();
        });
        
        $("#btnCancel").on('click', function(){
            $("#modal-$tableName").modal("toggle");
        });

        $('#modal-$tableName').on('hidden.bs.modal', function () {
            clearModal();
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
                    if(data.success == true)
                    {
                        swal(data.title, data.message, "success");
                        $("#modal-$tableName").modal("toggle");
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
        
    });

    var clearModal = function(){
        var frm = {};
        frm.ID = "$frmName";
        var defaultvalues = {};
        $.extend(defaultvalues,{'$tableName-idcatalogdetail':$model->Id});
        frm.DEFAULTS = defaultvalues;
        clearForm(frm);
    };
JS;
$this->registerJs($js);


$script = <<< JS
    var refreshGrid = function(){
        $.pjax.reload({container:'#details', async: false});
    };
        
    var editDetail = function(id){
        var params = {};
        var data = {'Id':id};
        params.URL = "$url/get";
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
            frm.SEPARATORS = ["["];
            frm.DATA = data;
            setValuesForm(frm);
            $("#modal-$tableName").modal();
        };
        params.ERROR = function(data){
            swal("ERROR "+data.code, data.message, "error");
        };
        AjaxRequest(params);
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
JS;
$this->registerJs($script, yii\web\View::POS_HEAD);
?>
