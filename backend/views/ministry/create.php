<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Ministries */

$this->title = 'Agregar Ministerio';
$this->params['breadcrumbs'][] = ['label' => 'Ministerio', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$tableName = $model->tableName();
$formName = $tableName."-form";
?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h4 class="panel-title"><?= Html::encode($this->title) ?></h4>
    </div>
    <?= $this->render('_form', [
        'model' => $model, 'formName' => $formName,
    ]) ?>
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
<?php 
$script = <<< JS
   $(document).ready(function(){
        $("#btnSave").on('click', function(){
            $("#$formName").submit();
        });
   });
JS;
$this->registerJs($script, $this::POS_READY);
?>