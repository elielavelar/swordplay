<?php

use kartik\widgets\ActiveForm;
use backend\models\Ministryperiodvoting;
/* @var $this \yii\web\View */
/* @var $model backend\models\Ministryperiodvoting */
/* @var $form kartik\widgets\ActiveForm */
$controller = Yii::$app->controller->id;
$url = Yii::$app->getUrlManager()->createUrl($controller);
$tableName = $model->tableName();
$formName = $tableName."-form";

$form = ActiveForm::begin([
    'id' => $formName
]);
?>
<?= $form->field($model, 'Id')->hiddenInput()->label(false); ?>
<?php ActiveForm::end();?>
<?php
$js = <<< JS
   $(document).ready(function(){
        
        $("#$formName").submit();
        
        $("#$formName").on('beforeSubmit',function(){
            var data = new FormData(document.getElementById('$formName'));
            var params = {};
            params.URL = '$url/getreportdata';
            params.DATA = data;
            params.DATATYPE = 'json';
            params.METHOD = 'POST';
            params.CACHE = false;
            params.PROCESSDATA = false;
            params.CONTENTTYPE = false;
            params.SUCCESS = function(data){
                console.log(data);
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
        
?>