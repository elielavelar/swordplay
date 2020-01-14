<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model common\models\Member */

$this->title = 'Actualizar Miembro: ' . $model->Code." - ".$model->displayName;;
$this->params['breadcrumbs'][] = ['label' => 'Miembros', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Code, 'url' => ['view', 'id' => $model->Id]];
$this->params['breadcrumbs'][] = 'Actualizar';

$url = Yii::$app->getUrlManager()->createUrl('member');

$controller = Yii::$app->controller->id;
$create = Yii::$app->customFunctions->userCan($controller.'Create');
$update = Yii::$app->customFunctions->userCan($controller.'Update');
$delete = Yii::$app->customFunctions->userCan($controller.'Delete');
$view = Yii::$app->customFunctions->userCan($controller.'View');

$permissions = [
    'create' => $create, 'update' => $update, 'view'=> $view,
    'delete'=> $delete
];

$tableName = $model->tableName();
$formName = $tableName."-form";
?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h4 class="panel-title"><?= Html::encode($this->title) ?></h4>
    </div>
    <?= Tabs::widget([
            'items' => [
                [
                    'label' => 'General',
                    'content' => $this->render('_form', ['model' => $model, 'formName' => $formName, 'modelDetail' => $attachmentModel]),
                    'active' => true
                ],
                /*
                [
                    'label' => 'Cargos',
                    'content' => $this->render('_form/_profiles',[
                        'model'=>$model, 'searchModel'=>$searchModelProfile, 'modelDetail'=>$modelDetailProfile, 'dataProvider' => $dataProviderProfile
                        ]),
                ],
                 * 
                 */
                [
                    'label' => 'Adjuntos',
                    'content' => $this->render('_form/_attachments', array_merge($permissions,[
                        'model'=>$model,
                        'searchModel'=>$searchAttachmentModel, 
                        'dataProvider'=>$attachmentDataProvider,'modelDetail'=>$attachmentModel,])
                    ),
                ],
                 
            ]]);
     ?>
    <div class="panel-footer">
        <div class="row">
            <div class="col-md-12">
                <span class="pull-right">
                    <?= Html::button('<i class="fas fa-save"></i> Guardar', ['type'=> 'button','class' => 'btn btn-success' , 'id' => 'btnSave']) ?>
                    <?= Html::a('<i class="fas fa-times"></i> Cancelar', ['index'], ['class' => 'btn btn-danger', 'id' => 'btnCancel']) ?>
                </span>
            </div>
        </div>
    </div>
</div>
<?= $this->render('_form/_attachmentModal', ['model' => $attachmentModel])?>
<?php 
$script = <<< JS
   $(document).ready(function(){
        $("#btnSave").on('click', function(){
            $("#$formName").submit();
        });
        
        $("#btnAddPhoto").on('click', function(){
            $("#modal-memberophoto").modal();
        });
        
        $("#btnAddAttachment").on('click', function(){
            $("#modal-attachment").modal();
        });
   });
JS;
$this->registerJs($script, $this::POS_READY);

$js = <<< JS
    var refreshImage = function(){
        var data = {Id: $model->Id};
        var params = {};
        params.URL = '$url/get';
        params.DATA = {'data':JSON.stringify(data)};
        params.DATATYPE = 'json';
        params.METHOD = 'post';
        params.SUCCESS = function(data){
        $("#$tableName-idattachmentpicture").val(data.Id);
            $("#member-picture").attr('src',data.path);
            refreshGridAttachments();
        };
        params.ERROR = function(data){
            swal("ERROR "+data.code, data.message, "error");
        };
        AjaxRequest(params);
    };
JS;
$this->registerJs($js, $this::POS_HEAD);
?>
