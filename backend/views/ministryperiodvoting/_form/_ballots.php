<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use backend\models\Ministryperiodvoting ;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\Ministryperiodvoting */
/* @var $modelDetail backend\models\Ministryvotingballot */
/* @var $searchModel backend\models\MinistryvotingballotSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$url = Yii::$app->getUrlManager()->createUrl('ministryvotingballot');

$filterState = $modelDetail->getStates();
$filterUser = $modelDetail->getUsers();

$create = Yii::$app->customFunctions->userCan('ministryperiodvotingCreate');
$update = Yii::$app->customFunctions->userCan('ministryperiodvotingUpdate');
$delete = Yii::$app->customFunctions->userCan('ministryperiodvotingDelete');
$view = Yii::$app->customFunctions->userCan('ministryperiodvotingView');

$template = "";
$template .= $view ? " {view} " : "";
#$template .= $update ? " {update} " : "";
$template .= $delete ? "&nbsp;|&nbsp;&nbsp;&nbsp; {delete} " : "";

$tableName = $modelDetail->tableName();
$modalName = 'modal-'.$tableName;
$formName = $tableName."-form";
$modalName = 'modal-'.$tableName;

$modalNullName = 'modal-null-'.$tableName;
$tableNullName = $tableName.'null';
$formNullName = $tableName."-null-form";

$gridName = $tableName."-grid";

$ministryName = $model->ministryPeriod->ministryServiceCentre->ministry->Name;

?>
<div class="panel panel-default">
    <div class="panel-body">
        <?php if($create):?>
        <div class='row'>
            <div class="col-md-12">
                <span class="pull-left">
                    <?= Html::button('<i class="fas fa-ban"></i> Anular Rango', ['type' => 'button', 'class' => 'btn btn-warning', 'id' => 'btnNullBallot']) ?>
                </span>
                <span class="pull-right">
                    <?= Html::button('<i class="fas fa-plus-circle"></i> Agregar', ['type' => 'button', 'class' => 'btn btn-success', 'id' => 'btnAddBallot']) ?>
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
                       # 'Id',
                       'Number',
                        [
                            'attribute' => 'IdState',
                            'filter' => $filterState,
                            'content' => function($model) {
                                return $model->IdState ? $model->state->Name : '';
                            },
                        ],
                        [
                            'attribute' => 'IdUserCreate',
                            'filter' => $filterUser,
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
                                return $model->IdUserCreate ? $model->userCreate->DisplayName: "";
                            },
                            'width' => '18%',
                            'contentOptions' => [
                                'style' => 'font-size:12px',
                            ]
                        ],
                        [
                            'attribute' => 'IdUserUpdate',
                            'filter' => $filterUser,
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
                                return $model->IdUserUpdate ? $model->userUpdate->DisplayName: "";
                            },
                            'width' => '18%',
                            'contentOptions' => [
                                'style' => 'font-size:12px',
                            ]
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => $template,
                            'buttons' => [
                                
                                'view' => function ($url, $model) {
                                    #$urlDetail = \Yii::$app->getUrlManager()->createUrl(['ministryservicecentre/update','id'=>$model->Id]);
                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', "javascript:getBallotDetail($model->Id)", [
                                                'title' => Yii::t('app', 'lead-view'),
                                    ]);
                                },
                                'delete' => function ($url, $model) {
                                    #$urlDetail = \Yii::$app->getUrlManager()->createUrl(['settingsdetail/delete','id'=>$model->Id]);
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', "javascript:deleteBallotDetail($model->Id);", [
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
<?=$this->render('_modalNullBallot', ['model' => $modelDetail, 'formName' => $formNullName, 'tableName' => $tableNullName, 'modalName' => $modalNullName]); ?>
<?=$this->render('_modalBallot', ['model' => $modelDetail, 'formName' => $formName, 'tableName' => $tableName, 'modalName' => $modalName]); ?>
<?php
$js = <<< JS
   
   $(document).ready(function(){
        
        $("#btnNullBallot").on('click', function(){
            $("#$modalNullName").modal();
        });
        
        $('#$modalName').on('hidden.bs.modal', function () {
            clearModalNullBallot();
        });
        
        $("#btnNullCancel").on('click', function(){
            $("#$modalNullName").modal('toggle');
        });
        
        $("#btnBallotCancel").on('click', function(){
            $("#$modalName").modal('toggle');
        });
        
        $('#$modalName').on('hidden.bs.modal', function () {
            clearModalBallot();
        });
   });
JS;
$this->registerJs($js, $this::POS_READY);

$jsHead = <<< JS
   var getBallotDetail = function(id){
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
            $("#vote-content").html(data.votes);
            $("#$modalName").modal();
        };
        params.ERROR = function(data){
            swal("ERROR "+data.code, data.message, "error");
        };
        AjaxRequest(params);
   };
        
   var deleteBallotDetail = function(){
       
   };
    
    var clearModalNullBallot = function(){
        var frm = {};
        frm.ID = "$formNullName";
        var defaultvalues = {};
        frm.DEFAULTS = defaultvalues;
        frm.EXTRA = function(){
            $("#$tableNullName-idvoting").val('$model->Id');
        };
        clearForm(frm);
    };
        
    var clearModalBallot = function(){
        var frm = {};
        frm.ID = "$formName";
        var defaultvalues = {};
        frm.DEFAULTS = defaultvalues;
        frm.EXTRA = function(){
            $("#vote-content").empty();
            $("#$formName input, #$formName textarea,#$formName select").attr('disabled',true);
            $('#ballot-tab li:first-child a').tab('show') ;
        };
        clearForm(frm);
    };
JS;
$this->registerJs($jsHead, $this::POS_HEAD);
?>