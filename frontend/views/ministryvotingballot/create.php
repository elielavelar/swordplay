<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Ministryvotingballot */

$this->title = 'Registrar Boleta';
$this->params['breadcrumbs'][] = ['label' => 'Votacion', 'url' => ['ministryperiodvoting/index']];
$this->params['breadcrumbs'][] = $this->title;

$url = Yii::$app->getUrlManager()->createUrl('ministryvotingballot');

$tableName = $model->tableName();
$formName = $tableName . '-form';
?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="row">
            <?= $this->render('_form', [
                'model' => $model, 'formName' => $formName, 'tableName' => $tableName,
            ]); ?>
            <div class="col-md-10">
                <span class="pull-right">
                    <?= Html::button('<i class="fas fa-trash"></i> Limpiar', ['type' => 'button', 'class' => 'btn btn-default btn-lg btnClean']); ?>
                </span>
            </div>
        </div>
    </div>
</div>
<?php
$script = <<< JS
   $(document).ready(function(){
        $("#$tableName-Number").on('change', function(){
            $("#$formName").submit();
        }).focus();
        
        $(".btnClean").on('click', function(){
            $("#$tableName-Number").val('').focus();
        });
        
        $("#$formName").on('beforeSubmit',function(){
            var data = new FormData(document.getElementById('$formName'));
            var params = {};
            params.URL = '$url/get';
            params.DATA = data;
            params.DATATYPE = 'json';
            params.METHOD = 'POST';
            params.CACHE = false;
            params.PROCESSDATA = false;
            params.CONTENTTYPE = false;
            params.SUCCESS = function(data){
                window.location = '$url/update/'+data.Id;
            };
            params.ERROR = function(data){
                swal({html:true,title:"Error: "+data.code, text: data.message, type:"error"});
                if(data.errors){
                    var errors = {};
                    errors.ID = "$formName";
                    errors.PREFIX = "$tableName-";
                    errors.ERRORS = data.errors;
                    errors.EXTRA = function(){};
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
    
JS;
?>
