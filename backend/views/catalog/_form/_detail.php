<?php
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\helpers\Html;
use common\models\State;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;
use common\models\Catalogs;
use common\models\Catalogversions;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/* @var $this yii\web\View */
/* @var $model \common\models\Catalogs */
/* @var $searchModel \backend\models\ActivetypeSearch;
 */
$tableName = $modelDetail->tableName();
$frmName = 'form-'.$tableName;

$template = "";
$template .= Yii::$app->customFunctions->userCan("catalogView") ? "{view} ":"";
$template .= Yii::$app->customFunctions->userCan("catalogUpdate") ? "{edit} {update} ":"";
$template .= Yii::$app->customFunctions->userCan("catalogDelete") ? " |&nbsp;&nbsp;&nbsp;&nbsp;{delete} ":"";

$filterState = ArrayHelper::map(State::findAll(['KeyWord'=> StringHelper::basename(Catalogversions::class)]), 'Id', 'Name');

$url = Yii::$app->getUrlManager()->createUrl('catalogversion');

?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <span class="pull-right">
                    <?= Yii::$app->customFunctions->userCan('catalogCreate') ? 
                        Html::button("<i class='fas fa-plus'></i> Agregar Versión", ['class'=>'btn btn-success','id'=>'btnAddDetail'])
                        :"";
                    ?>
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
                    'Version',
                    [
                        'attribute'=>'IdState',
                        'filter'=> Html::activeDropDownList($searchModel, 'IdState', $filterState, ['class'=>'form-control','prompt'=>'--']),
                        'content'=>function($data){
                            return $data->IdState != 0 ? $data->state->Name:NULL;
                        },
                        'enableSorting'=>TRUE  
                    ],
                    'Description',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => $template,
                        'buttons'=>[
                            'edit' => function ($url, $model) {
                                #$urlDetail = \Yii::$app->getUrlManager()->createUrl(['settingsdetail/delete','id'=>$model->Id]);
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', "javascript:editDetail($model->Id);", [
                                            'title' => Yii::t('app', 'Actualizar Version'), 
                                ]);
                            },
                            'update' => function ($url, $model) {
                                #$urlDetail = \Yii::$app->getUrlManager()->createUrl(['settingsdetail/delete','id'=>$model->Id]);
                                return Html::a('<span class="glyphicon glyphicon-th-large"></span>', "javascript:updateDetail($model->Id)", [
                                            'title' => Yii::t('app', 'Detalle de Version'), 
                                ]);
                            },
                            'delete' => function ($url, $model) {
                                #$urlDetail = \Yii::$app->getUrlManager()->createUrl(['settingsdetail/delete','id'=>$model->Id]);
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', "javascript:deleteDetail($model->Id);", [
                                            'title' => Yii::t('app', 'Eliminar Version'), 
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
<?=$this->render('_formVersion', ['model'=> $modelDetail])?>
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
        $.extend(defaultvalues,{'$tableName-idcatalog':$model->Id});
        frm.DEFAULTS = defaultvalues;
        clearForm(frm);
    };
JS;
$this->registerJs($js);


$script = <<< JS
    var refreshGrid = function(){
        $.pjax.reload({container:'#details', async: false});
    };
        
    var updateDetail = function(id){
        window.location = '$url/update/'+id;
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
