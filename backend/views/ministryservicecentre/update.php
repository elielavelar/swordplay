<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model backend\models\Ministryservicecentres */

$this->title = 'Actualizar Ministerio de Filial: ' . $model->ministry->Name;
$this->params['breadcrumbs'][] = ['label' => 'Ministerios', 'url' => ['ministry/index']];
$this->params['breadcrumbs'][] = ['label' => $model->ministry->Name, 'url' => ['ministry/view','id' => $model->IdMinistry]];
$this->params['breadcrumbs'][] = 'Ministerio de Filiales';
$this->params['breadcrumbs'][] = ['label' => $model->serviceCentreName, 'url' => ['view', 'id' => $model->Id]];
$this->params['breadcrumbs'][] = 'Actualizar';

$tableName = $model->tableName();
$formName = $tableName.'-form';
?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h4 class="panel-title"><?= Html::encode($this->title) ?></h4>
    </div>
    <?= Tabs::widget([
            'items' => [
                [
                    'label' => 'General',
                    'content' => $this->render('_form', ['model' => $model, 'formName' => $formName]),
                    'active' => true
                ],
                [
                    'label' => 'Periodos',
                    'content' => $this->render('_form/_periods',[
                        'model'=>$model, 'searchModel'=>$searchModel, 'modelDetail'=>$modelDetail, 'dataProvider' => $dataProvider
                    ]),
                ],
            ]]);
     ?>
    <div class="panel-footer">
        <div class="row">
            <div class="col-md-12">
                <span class="pull-right">
                    <?= Html::button('<i class="fas fa-save"></i> Guardar',['type'=>'button','class' => 'btn btn-success','id' => 'btnSave'])?>
                    <?= Html::a('<i class="fas fa-times"></i> Cancelar',['ministry/view','id' => $model->IdMinistry],['class' => 'btn btn-danger','id' => 'btnCancel'])?>
                </span>
            </div>
        </div>
    </div>
</div>
<?php
$js = <<< JS
   $(document).ready(function(){
        $("#btnSave").on('click', function(){
            $("#$formName").submit();
        });
   });
JS;
$this->registerJs($js, $this::POS_READY);
?>
