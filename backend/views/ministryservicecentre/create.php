<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Ministryservicecentres */

$this->title = 'Agregar Ministerio a Filial: '.$model->ministry->Name;
$this->params['breadcrumbs'][] = ['label' => 'Ministerios', 'url' => ['ministry/index']];
$this->params['breadcrumbs'][] = ['label' => $model->ministry->Name, 'url' => ['ministry/view','id' => $model->IdMinistry]];
$this->params['breadcrumbs'][] = 'Agregar a Filial';

$tableName = $model->tableName();
$formName = $tableName.'-form';
?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h4 class="panel-title"><?= Html::encode($this->title) ?></h4>
    </div>
    <?= $this->render('_form', [
        'model' => $model, 'formName' => $formName, 'tableName' => $tableName,
    ]) ?>
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