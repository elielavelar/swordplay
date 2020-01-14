<?php
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\helpers\Html;
use common\models\State;
use common\models\Type;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;
use common\models\Catalogs;
use common\models\Catalogversions;
use common\models\Catalogdetails;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/* @var $this yii\web\View */
/* @var $model \common\models\Catalogs */
/* @var $searchModel \backend\models\CatalogdetailSearch;
/* @var $modelDetail common\models\Catalogdetails;
 */
$tableName = $modelDetail->tableName();
$frmName = 'form-'.$tableName;

$template = "";
$template .= Yii::$app->customFunctions->userCan("catalogdetailView") ? "{view} ":"";
$template .= Yii::$app->customFunctions->userCan("catalogdetailUpdate") ? "{edit} {update} ":"";
$template .= Yii::$app->customFunctions->userCan("catalogdetailDelete") ? " |   {delete} ":"";

$url = Yii::$app->getUrlManager()->createUrl('catalogdetail');

?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <span class="pull-right">
                    <?= Yii::$app->customFunctions->userCan('catalogCreate') ? 
                        Html::a("<i class='fas fa-plus-circle'></i> Agregar Detalle",['catalogdetail/create','id'=> $model->Id], ['class'=>'btn btn-success','id'=>'btnAddDetail'])
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
                    'Name',
                    'KeyWord',
                    [
                        'attribute'=>'Code',
                        'headerOptions' => ['style' => 'width:10%'],
                    ],
                    [
                        'attribute'=>'IdType',
                        'filter'=> Html::activeDropDownList($searchModel, 'IdType', $modelDetail->getTypes(), ['class'=>'form-control','prompt'=>'--']),
                        'content'=>function($data){
                            return $data->IdState != 0 ? $data->state->Name:NULL;
                        },
                        'headerOptions' => ['style' => 'width:10%'],
                        'enableSorting'=>TRUE  
                    ],
                    [
                        'attribute'=>'IdState',
                        'filter'=> Html::activeDropDownList($searchModel, 'IdState', $modelDetail->getStates(), ['class'=>'form-control','prompt'=>'--']),
                        'content'=>function($data){
                            return $data->IdState != 0 ? $data->state->Name:NULL;
                        },
                        'headerOptions' => ['style' => 'width:10%'],
                        'enableSorting'=>TRUE  
                    ],
                    #'Description',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => $template,
                        'buttons'=>[
                            'update' => function ($url, $model) {
                                #$urlDetail = \Yii::$app->getUrlManager()->createUrl(['catalogdetail/update','id'=>$model->Id]);
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', "javascript:editDetail($model->Id);",  [
                                            'title' => Yii::t('app', 'Editar Version'), 
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
<?php
$js = <<< JS
   
JS;
$this->registerJs($js);


$script = <<< JS
    var refreshGrid = function(){
        $.pjax.reload({container:'#details'});
    };
        
    var editDetail = function(id){
        window.location = '$url/update/'+id;
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
