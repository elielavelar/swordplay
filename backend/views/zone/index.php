<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\State;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use common\models\Zones;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\ZoneSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Zonas';
$this->params['breadcrumbs'][] = $this->title;

$tableName = $model->tableName();
$formName = $tableName."-form";
$url = \Yii::$app->getUrlManager()->createUrl('zone');
?>
<div class="zones-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::button('Agregar Zona', ['id'=>'btn-addZone','class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin([
    'id'=>'details',
]);?>
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'Name',
            'Code',
            [
                'attribute'=>'IdState',
                'filter'=> ArrayHelper::map(State::find()->where(['KeyWord'=>  StringHelper::basename(Zones::className())])->select(['Id','Name'])->all(), 'Id', 'Name'),
                'content'=>function($model){
                    return $model->IdState ? $model->state->Name:"";
                },
            ],
            'Description:ntext',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {cancel}',
                'buttons'=>[
                    'view'=>function ($url, $model) {
                        return $model->view ? "<a href='javascript:viewDetail($model->Id)' title='Ver Zona'>"
                                . "<span class='glyphicon glyphicon-eye-open'></span></a>":"";
                    },
                    'update'=>function ($url, $model) {
                        return $model->update ? "<a href='javascript:editDetail($model->Id)' title='Editar Zona'>"
                                . "<span class='glyphicon glyphicon-pencil'></span></a>":"";
                    },
                    'delete'=>function ($url, $model) {
                        return $model->delete ? (Html::a("<span class='glyphicon glyphicon-trash'></span>",$url,[
                            'data' => [
                                'confirm' => 'Está seguro que desea Eliminar este Registro?',
                                'method' => 'post',
                            ],
                        ])):"";
                    },
                ],
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
    <?= $this->render('_form', [
        'model' => $model
    ]) 
?>
<?php
$script = <<< JS
   $(document).ready(function(){
        $('#btn-addZone').on('click',function(){
            $("#modal-detail").modal();
        });
        
        $('#modal-detail').on('hidden.bs.modal', function () {
            clearModal();
        });
        
        $("#btn-cancel").on('click',function(){
            $("#modal-detail").modal("toggle");
        });
        
        $("#btn-edit").on('click',function(){
            var id = $("#$tableName-id").val();
            editDetail(id);
        });
        
        $("#$formName").on('beforeSubmit',function(){
            $.ajax({
                url: "$url/save",
                type: "POST",
                data:  new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData:false,
                success: function(data)
                {
                    //var data = JSON.parse(data);
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
                            errors.ID = "$formName";
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
        frm.ID = "$formName";
        var defaultvalues = {};
        //$.extend(defaultvalues,{});
        frm.DEFAULTS = defaultvalues;
        clearForm(frm);
        $("#$formName button[type=submit]").show();
        $("#btn-edit").hide();
    };
JS;
$this->registerJs($script, yii\web\View::POS_READY);

$script = <<< JS
    var refreshGrid = function(){
        $.pjax.reload({container:'#details'});
   };
        
   var editDetail = function(id){
        $("#$formName input[type=text], #$formName textarea, #$formName select").removeAttr("disabled");
        $("#$formName button[type=submit]").show();
        $("#btn-edit").hide();
        getDetail(id);
   };
        
   var viewDetail = function(id){
        $("#$formName input[type=text], #$formName textarea, #$formName select").attr("disabled",true);
        $("#$formName button[type=submit]").hide();
        $("#btn-edit").show();
        getDetail(id);
   };
        
   var getDetail = function(id){
        var params = {};
        var data = {'id':id};
        params.URL = "$url/get/";
        params.DATA = {'data':JSON.stringify(data)},
        params.METHOD = 'POST';
        params.DATATYPE = 'json';
        params.SUCCESS = function(data){
            var frm = {};
            frm.ID = "$formName";
            frm.PREFIX = "$tableName-";
            frm.UNBOUNDNAME = true;
            frm.MATCHBYNAME = true;
            frm.SEPARATORS = ["["];
            frm.REPLACESTRING = {']':''};
            frm.DATA = data;
            setValuesForm(frm);
            $("#modal-detail").modal();
        };
        params.ERROR = function(data){
            $("#$formName input[type=text], #$formName textarea, #$formName select").removeAttr("disabled");
            swal("ERROR "+data.code, data.message, "error");
        };
        AjaxRequest(params);
    };
JS;
$this->registerJs($script, $this::POS_HEAD);

?>
